(($) => {
  $(() => {
    var miqid = window.miqid || {ajax: undefined};
    const {__, _x, _n, _nx} = wp.i18n;

    $('.miqid-admin').on('click', '.btn-save', (e) => {
      let _btn = $(e.currentTarget),
          _inputs = $('.miqid-admin :input'),
          _Options = {action: 'save_miqid'};

      _btn.addClass('miqid-loading');

      $.each(_inputs, (i, inp) => {
        let _input = $(inp),
            _name = _input.attr('name'),
            _val = _input.val();

        if (_name === undefined)
          return;

        if ((_input.is(':checkbox') && !_input.is(':checked')) ||
            (_input.is(':radio') && !_input.is(':checked')))
          _val = '';

        if (_Options[_name] !== undefined) {
          if (!_.isArray(_Options[_name]))
            _Options[_name] = [_Options[_name]];

          _Options[_name].push(_val);
        } else {
          _Options[_name] = _val;
        }
      });

      fetch(miqid.ajax, {
        'method': 'POST',
        'body': new URLSearchParams(_Options),
      }).then(() => _btn.removeClass('miqid-loading'));
    });

    /*$('.plugins-php #wpwrap .wrap table.plugins').on('click', '#deactivate-miqid-core', (e) => {
      e.preventDefault();
      let _link = $(e.currentTarget);

      let _feedbackBox = $(`
<div id="miqid-core-feedback">
    <div class="__overlay">
        <div class="__wrapper">
            <h3 class="__heading">${__('Hjælp os med at blive bedre')}</h3>
            <div class="__content">
                <p>${__('Hvis du har et øjeblik, kan du dele, hvorfor du ønsker at deaktivere MIQID-Core')}</p>
                <ul class="__options">
                    <li>
                        <label>
                            <input type="radio" name="feedback" value="no_longer_needed">
                            <span>${__('Jeg behøver ikke længere dette plugin')}</span>
                        </label>
                    </li>
                    <li>
                        <label>
                            <input type="radio" name="feedback" value="found_a_better_plugin">
                            <span>
                            <span>${__('Jeg har fundet bedre plugin')}</span>
                            <sub><textarea name="feedback-text"></textarea></sub>
                        </span>
                        </label>
                    </li>
                    <li>
                        <label>
                            <input type="radio" name="feedback" value="couldnt_get_the_plugin_to_work">
                            <span>${__('Jeg kan ikke få det til at virke')}</span>
                        </label>
                    </li>
                    <li>
                        <label>
                            <input type="radio" name="feedback" value="temporary_deactivation">
                            <span>${__('Der har været for mange problemer med dette plugin')}</span>
                        </label>
                    </li>
                    <li>
                        <label>
                            <input type="radio" name="feedback" value="temporary_deactivation">
                            <span>${__('Det er en midlertidig deaktivering')}</span>
                        </label>
                    </li>
                    <li>
                        <label>
                            <input type="radio" name="feedback" value="another reason">
                            <span>
                            <span>${__('Andet')}</span>
                            <sub><textarea name="feedback-text"></textarea></sub>
                        </span>
                        </label>
                    </li>
                </ul>
            </div>
            <div class="__buttons">
                <button type="button" class="button button-link disable">${__('Disable without submit')}</button>
                <button type="button" class="button button-primary submit-and-disable" disabled>${__('Submit and Disable')}</button>
            </div>
        </div>
    </div>
</div>
`);
      $('#miqid-core-feedback').remove();

      const {adminAjax} = miqid_core_admin;
      fetch(adminAjax + '?action=deactivate_fetch_options').then(status).then(json).then(resp => {

        $(`body`).append(_feedbackBox);

      }).catch(err => console.log(err));

      _feedbackBox.on('click', '.__overlay', (e) => {
        let _current = $(e.target);
        if (_current.hasClass('__overlay'))
          _feedbackBox.remove();
      });

      _feedbackBox.on('change', ':input[name="feedback"]',
          () => _feedbackBox.find('button.submit-and-disable').prop('disabled', false));

      _feedbackBox.on('click', '.__buttons button', (btn) => {
        let _button = $(btn.currentTarget);

        if (_button.hasClass('close'))
          _feedbackBox.remove();
        else if (_button.hasClass('disable'))
          location.href = _link.attr('href');
        else if (_button.hasClass('submit-and-disable'))
          submitAndDisable(_link);
      });

    });*/

    const submitAndDisable = (link) => {
      let _link = $(link),
          _feedback = $('#miqid-core-feedback'),
          _input_feedback = _feedback.find(':input[name="feedback"]:checked'),
          _input_feedback_text = _input_feedback.parent().find(':input[name="feedback-text"]');

      const {adminAjax} = miqid_core_admin;
      fetch(adminAjax, {
        method: 'POST',
        body: new URLSearchParams({
          action: 'deactivate_save_feedback',
          feedback: _input_feedback.val() || '',
          feedback_text: _input_feedback_text.val(),
        }),
      }).then(status).then(json).then(resp => {
        location.href = _link.attr('href');
      }).catch(err => console.log(err));

    };

    const status = (response) => {
      if (response.status >= 200 && response.status < 300) {
        return Promise.resolve(response);
      } else {
        return Promise.reject(new Error(response.statusText));
      }
    };

    const json = (response) => {
      return response.json();
    };
  });
})(jQuery);