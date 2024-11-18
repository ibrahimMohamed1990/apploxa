jQuery(document).ready(function ($) {




  $('#apploxa_logs_t').DataTable({
       "paging": true,
       "pagingType": "simple_numbers",
       "order": [[0, 'desc']]// Options: 'simple', 'simple_numbers', 'full', 'full_numbers'
   });
  toastr.options = {"positionClass": "toast-bottom-right","rtl":true}
  $(document).on("click","._qck-send-btn",function(e) {
        var btn = $(this);
        btn.append('  <span class="spinner-border spinner-grow-sm"></span>  ');
        btn.attr('disabled',true);
    $.post(ajaxurl, {
            'phone':$('#whatsapp-number').val(),
            'message':$('._qck-textarea').val(),
            'action': 'apploxa_send_quick_msg',
			'apploxa_admin_nonce':$('#apploxa_admin_nonce').val()
           }, function (response){
              if(response.success){
                toastr.success(response.data)
              }else {
                toastr.error(response.data)
              }

                $('.spinner-border').remove();
                btn.removeAttr('disabled');

           }).fail(function(xhr, status, error) {
             toastr.error('something went wrong!')
             $('.spinner-border').remove();
             btn.removeAttr('disabled');
            });
 });

  $(document).on("change","._cust-dropdown",function(e) {
      $(this).next('textarea').val($(this).val());
  });




});
