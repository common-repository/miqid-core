(($) => {
  const RefreshInterval = 5000;
  const MaxRunTimeInterval = 10;
  const regexDataNotSharedWith = /Data ikke delt med \w+ i MIQID\./i;
  $(() => {
    $(`*[data-miqid-field]:visible`).each((i, field) => {
      let _field = $(field),
          _element = _field.find('> *:first').length > 0 ? _field.find('> *:first') : _field;
      while (_element.find('> *:first').length > 0) {
        _element = _element.find('> *:first');
      }

      let _value = _element.val() || _element.html();

      _element.toggleClass('MIQIDSmallText', regexDataNotSharedWith.test(_value));
      _element.parent('.miqid-verified').toggleClass('miqid-not-verified', regexDataNotSharedWith.test(_value));
    });

    Delay('FetchMIQIDValue', () => FetchMIQIDValue(), RefreshInterval);
  });

  const FetchMIQIDValue = (refresh_counter = 0, max_run_time = MaxRunTimeInterval) => {
    refresh_counter++;
    run_time = (refresh_counter * RefreshInterval / 1000 / 60);

    if (run_time >= max_run_time) {
      timeoutOverlay(refresh_counter, max_run_time);
      return;
    }

    let _MIQIDFields = {},
        queryString = window.location.search,
        urlParams = new URLSearchParams(queryString);
    $(`*[data-miqid-field]:visible`).each((i, field) => {
      let _field = $(field),
          _miqid_field = _field.attr('data-miqid-field'),
          _element = _field.find('> *:first').length > 0 ? _field.find('> *:first') : _field;
      while (_element.find('> *:first').length > 0) {
        _element = _element.find('> *:first');
      }

      if (_.isEmpty(_MIQIDFields[_miqid_field])) {
        if (_element.is('input'))
          _MIQIDFields[_miqid_field] = _element.val();
        else if (_element.is('img'))
          _MIQIDFields[_miqid_field] = _element[0].outerHTML;
        else
          _MIQIDFields[_miqid_field] = _element.html();
      }
    });

    fetch('/wp-admin/admin-ajax.php', {
      method: 'POST',
      body: getFormData({
        action: 'get_miqid_value',
        Permission: urlParams.get('Permission') || 'Private',
        'MIQIDFields': JSON.stringify(_MIQIDFields),
      }),
    }).
        then(resp => [200].includes(resp.status)
            ? Promise.resolve(resp)
            : Promise.reject(resp)).
        then(resp => resp.json()).
        then(resp => {

          $.each(resp, (key, new_value) => {
            key = key.replace(/\\/, '\\\\');
            let _fields = $(`*[data-miqid-field="${key}"]`);

            $.each(_fields, (f, field) => {
              let _field = $(field),
                  _element = _field.find('> *:first').length > 0 ? _field.find('> *:first') : _field;
              while (_element.find('> *:first').length > 0) {
                _element = _element.find('> *:first');
              }

              if (_element.is('img'))
                _element.replaceWith(new_value);
              else
                _element.
                    val(new_value).
                    text(new_value);

              _element.toggleClass('MIQIDSmallText', regexDataNotSharedWith.test(new_value));
              _element.parent('.miqid-verified').toggleClass('miqid-not-verified', regexDataNotSharedWith.test(_value));

            });
          });

          Delay('FetchMIQIDValue', () => FetchMIQIDValue(refresh_counter, max_run_time), RefreshInterval);
        }).
        catch(err => console.log(err));
  };

  const timeoutOverlay = (refresh_counter, max_run_time) => {
    max_run_time = max_run_time + MaxRunTimeInterval;
    let _body = $('body'),
        _timeoutOverlay = $(`<div class="timeout-overlay"
     style="position: fixed; top: 0; right: 0; bottom: 0; left: 0; background-color: rgba(0,0,0,.5); display: flex; justify-content: center; align-items: center; z-index: 100">
    <p style="color: #fff; padding: 15px; background-color: rgba(0,0,0,.75); border-radius: 10px;">
        Automatisk opdatering stoppet
    </p>
</div>`);

    _body.append(_timeoutOverlay);

    _timeoutOverlay.on('click', () => {
      Delay('FetchMIQIDValue', () => FetchMIQIDValue(refresh_counter, max_run_time), RefreshInterval);
      _timeoutOverlay.remove();
    });
  };

  const getFormData = (obj) => {
    const formData = new FormData();
    Object.keys(obj).forEach(key => formData.append(key, obj[key]));
    return formData;
  };

  const Delays = [];
  const Delay = (name, cb, timeout = 250) => {
    clearTimeout(Delays[name || 'Delay']);

    Delays[name || 'Delay'] = setTimeout(cb, timeout);
  };

})(jQuery);