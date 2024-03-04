(function( $ ) {
  'use strict';

  // Validator

  $.validator.setDefaults({
    errorElement: "div",
    errorClass: "pl-invalid-feedback",
    errorPlacement: function (error, element) {
      if (element.prop("type") === "checkbox") {
        error.insertAfter(element.next("label"));
      } else {
        error.insertAfter(element);
      }
    },
    highlight: function (element, errorClass, validClass) {
      $(element).addClass("pl-invalid-feedback");
    },
    unhighlight: function (element, errorClass, validClass) {
      $(element).removeClass("pl-invalid-feedback");
    },
  });

  $.validator.addMethod('phone', function (value) {
    return /^$|(^[+]{0,1}[0-9]{8,}$)/.test(value);
  }, pl_jobs_settings_obj.jquery_validate.phone_validation);

})( jQuery );
