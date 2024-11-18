//var apploxa_ajaxurl = '/wp-admin/admin-ajax.php';
function openTab(evt, tabName) {
    var apx_i, apx_tabcontent, apx_tablinks;
    apx_tabcontent = document.getElementsByClassName("apploxa_p_tabcontent");
    for (apx_i = 0; apx_i < apx_tabcontent.length; apx_i++) {
        apx_tabcontent[apx_i].style.display = "none";
    }
    apx_tablinks = document.getElementsByClassName("apploxa_p_tablinks");
    for (apx_i = 0; apx_i < apx_tablinks.length; apx_i++) {
        apx_tablinks[apx_i].className = apx_tablinks[apx_i].className.replace(" active", "");
    }
    document.getElementById(tabName).style.display = "block";
    evt.currentTarget.className += " active";
}
// Handle auto-focus and move to the next input
function apx_autoFocusOtp() {
    const apx_inputs = document.querySelectorAll('.apploxa_p_otp_input input');
    apx_inputs.forEach((input, index) => {
        input.addEventListener('input', () => {
            if (input.value.length === 1 && index < apx_inputs.length - 1) {
                apx_inputs[index + 1].focus();
            }
        });
    });
}
// Get OTP values from the inputs
function apx_getOtpValues() {
    const apx_otpValues = [];
    const apx_inputs = document.querySelectorAll('.apploxa_p_otp_input input');
    apx_inputs.forEach(input => {
        apx_otpValues.push(input.value);
    });
    return apx_otpValues.join(''); // Returns the OTP as a concatenated string
}
// Run the auto-focus function

var apx_modal = document.getElementById("apploxa_p_popup");

var apx_span = document.getElementsByClassName("apploxa_p_close")[0];
if(apx_modal){

  apx_span.onclick = function() {
      apx_modal.style.display = "none";
  }
  window.onclick = function(event) {
      if (event.target == apx_modal) {
          apx_modal.style.display = "none";
      }
  }
  apx_autoFocusOtp();
}


jQuery(document).ready(function ($) {



  $(document).on("click",'a[href="#apploxa-popup"]',function(e) {
      e.preventDefault();
      $('#apploxa_p_popup').css('display','block')

   });


   $(document).on("click",".apploxa_confirm_code",function(e) {
     e.preventDefault();
     const otpCode = apx_getOtpValues();
     $.post(apploxa_ajaxurl, {
             'otp':otpCode,
             'phone':$('.apploxa_hidn_phone').val(),
             'fullname':$('.apploxa_fullname').val() ?? '',
             'action': 'apploxa_check_otp',
             'apploxa_admin_nonce':$('#apploxa_admin_nonce').val()

            }, function (response){
               if(response.success){
                 toastr.success('Successfully login.')
                 window.location.href=response.data;
               }else {
                 toastr.error(response.data)
               }
            }).fail(function(xhr, status, error) {  
              toastr.error('Something went wrong! error:' + error)
             });
   });

   $(document).on("click",".apploxa_logn_btn",function(e) {
     e.preventDefault();
     let phone = $('.apploxa_phone').filter(':visible').val();
     let login = $(this).attr('login');
     $('.apploxa_hidn_phone').val(phone);
     $.post(apploxa_ajaxurl, {
             'phone':phone, 
             'action':'apploxa_check_user',
             'login':login,
             'apploxa_admin_nonce':$('#apploxa_admin_nonce').val()
            }, function (response){
               if(response.success){
                 toastr.success(response.data)
                 openTab(event, 'apploxa_p_otp');
                 $('.apploxa_p_otp_input .first_inp').focus();
               }else {
                 toastr.error(response.data)
               }
            }).fail(function(xhr, status, error) {
              console.log(xhr.responseText);
              toastr.error('Something went wrong! error:' + error)
             });
   });


});
