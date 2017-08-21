var Login = Login || {};

(function ($, window, undefined)
{
  "use strict";
  $(document).ready(function () {
    Login.$container = $("#form_login");
	
    Login.$container.validate({
      rules: {
        username: {
          required: true
        },
        password: {
          required: true
        }
      },
      highlight: function (element) {
        $(element).closest('.input-group').addClass('validate-has-error');
      },
      unhighlight: function (element)
      {
        $(element).closest('.input-group').removeClass('validate-has-error');
      },
      submitHandler: function (ev)
      {
		$(".login-page").addClass('logging-in');
        $(".form-login-error").slideUp('fast');
        setTimeout(function ()
        {
          var random_pct = 25 + Math.round(Math.random() * 30);
          Login.setPercentage(40 + random_pct);
          $.ajax({
            url: baseurl,
            method: 'POST',
            dataType: 'json',
            data: $("#form_login").serialize(),
            error: function (e)
            {
              console.log(e);
            },
            success: function (response)
            {
              var login_status = response.login_status;
              Login.setPercentage(100);
              setTimeout(function () {
                if (login_status === 'invalid') {
                  $(".login-page").removeClass('logging-in');
                  Login.resetProgressBar(true);
                }
                else
                if (login_status === 'success')
                {
                  setTimeout(function ()
                  {
                    var redirect_url = baseurl;
                    if (response.redirect_url && response.redirect_url.length) {
                      redirect_url = response.redirect_url;
                    }
                    window.location.href = redirect_url;
                  }, 400);
                }
              }, 1000);
            }
          });
        }, 650);
      }
    });
	
    var is_lockscreen = $(".login-page").hasClass('is-lockscreen');
    if (is_lockscreen) {
      Login.$container = $("#form_lockscreen");
      Login.$ls_thumb = Login.$container.find('.lockscreen-thumb');
      Login.$container.validate({
        rules: {
          password: {
            required: true
          },
        },
        highlight: function (element) {
          $(element).closest('.input-group').addClass('validate-has-error');
        },
        unhighlight: function (element) {
          $(element).closest('.input-group').removeClass('validate-has-error');
        },
        submitHandler: function (ev) {
          $(".login-page").addClass('logging-in-lockscreen');
          setTimeout(function ()
          {
            var random_pct = 25 + Math.round(Math.random() * 30);
            Login.setPercentage(random_pct, function () {
              setTimeout(function () {
                Login.setPercentage(100, function () {
                  setTimeout("window.location.href = '../../'", 600);
                }, 2);
              }, 820);
            });
          }, 650);
        }
      });
    }

    Login.$body = $(".login-page");
    Login.$login_progressbar_indicator = $(".login-progressbar-indicator h3");
    Login.$login_progressbar = Login.$body.find(".login-progressbar div");
    Login.$login_progressbar_indicator.html('0%');
    if (Login.$body.hasClass('login-form-fall')) {
      var focus_set = false;
      setTimeout(function () {
        Login.$body.addClass('login-form-fall-init')
        setTimeout(function () {
          if (!focus_set) {
            Login.$container.find('input:first').focus();
            focus_set = true;
          }
        }, 550);
      }, 0);
    }
    else {
      Login.$container.find('input:first').focus();
    }

    Login.$container.find('.form-control').each(function (i, el) {
      var $this = $(el),
              $group = $this.closest('.input-group');

      $this.prev('.input-group-addon').click(function () {
        $this.focus();
      });

      $this.on({
        focus: function () {
          $group.addClass('focused');
        },
        blur: function () {
          $group.removeClass('focused');
        }
      });
    });

    $.extend(Login, {
      setPercentage: function (pct, callback) {
        pct = parseInt(pct / 100 * 100, 10) + '%';

        if (is_lockscreen) {
          Login.$lockscreen_progress_indicator.html(pct);
          var o = {
            pct: currentProgress
          };

          TweenMax.to(o, .7, {
            pct: parseInt(pct, 10),
            roundProps: ["pct"],
            ease: Sine.easeOut,
            onUpdate: function ()
            {
              Login.$lockscreen_progress_indicator.html(o.pct + '%');
              drawProgress(parseInt(o.pct, 10) / 100);
            },
            onComplete: callback
          });
          return;
        }

        Login.$login_progressbar_indicator.html(pct);
        Login.$login_progressbar.width(pct);

        var o = {
          pct: parseInt(Login.$login_progressbar.width() / Login.$login_progressbar.parent().width() * 100, 10)
        };

        TweenMax.to(o, .7, {
          pct: parseInt(pct, 10),
          roundProps: ["pct"],
          ease: Sine.easeOut,
          onUpdate: function ()
          {
            Login.$login_progressbar_indicator.html(o.pct + '%');
          },
          onComplete: callback
        });
      },
      resetProgressBar: function (display_errors)
      {
        TweenMax.set(Login.$container, {css: {opacity: 0}});

        setTimeout(function ()
        {
          TweenMax.to(Login.$container, .6, {css: {opacity: 1}, onComplete: function ()
            {
              Login.$container.attr('style', '');
            }});

          Login.$login_progressbar_indicator.html('0%');
          Login.$login_progressbar.width(0);

          if (display_errors)
          {
            var $errors_container = $(".form-login-error");

            $errors_container.show();
            var height = $errors_container.outerHeight();

            $errors_container.css({
              height: 0
            });

            TweenMax.to($errors_container, .45, {css: {height: height}, onComplete: function ()
              {
                $errors_container.css({height: 'auto'});
              }});

            Login.$container.find('input[type="password"]').val('');
          }

        }, 800);
      }
    });


    if (is_lockscreen)
    {
      Login.$lockscreen_progress_canvas = $('<canvas></canvas>');
      Login.$lockscreen_progress_indicator = Login.$container.find('.lockscreen-progress-indicator');
      Login.$lockscreen_progress_canvas.appendTo(Login.$ls_thumb);
      var thumb_size = Login.$ls_thumb.width();
      Login.$lockscreen_progress_canvas.attr({
        width: thumb_size,
        height: thumb_size
      });

      Login.lockscreen_progress_canvas = Login.$lockscreen_progress_canvas.get(0);

      var bg = Login.lockscreen_progress_canvas,
              ctx = ctx = bg.getContext('2d'),
              imd = null,
              circ = Math.PI * 2,
              quart = Math.PI / 2,
              currentProgress = 0;

      ctx.beginPath();
      ctx.strokeStyle = '#eb7067';
      ctx.lineCap = 'square';
      ctx.closePath();
      ctx.fill();
      ctx.lineWidth = 3.0;
      imd = ctx.getImageData(0, 0, thumb_size, thumb_size);

      var drawProgress = function (current) {
        ctx.putImageData(imd, 0, 0);
        ctx.beginPath();
        ctx.arc(thumb_size / 2, thumb_size / 2, 70, -(quart), ((circ) * current) - quart, false);
        ctx.stroke();

        currentProgress = current * 100;
      }

      drawProgress(0 / 100);
      Login.$lockscreen_progress_indicator.html('0%');
      ctx.restore();
    }

  });

})(jQuery, window);