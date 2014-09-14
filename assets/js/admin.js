var Admin = {

  toggleLoginRecovery: function(){
    var is_login_visible = $('#modal-login').is(':visible');
    (is_login_visible ? $('#modal-login') : $('#modal-recovery')).slideUp(300, function(){
      (is_login_visible ? $('#modal-recovery') : $('#modal-login')).slideDown(300, function(){
        $(this).find('input:text:first').focus();
      });
    });
  }
   
};

$(function(){

  var loader = '<img src="'+ site_url +'assets/img/spinner.gif"/>';

  $('.toggle-login-recovery').click(function(e){
    Admin.toggleLoginRecovery();
    e.preventDefault();
  });

  $("#all").click(function(){
    $("#number_field").attr('disabled','disabled');
    $("#group_field").attr('disabled','disabled');
  });

  $("#group").click(function(){
    $("#number_field").attr('disabled','disabled');
    $("#group_field").removeAttr('disabled');
  });

  $("#number").click(function(){
    $("#number_field").removeAttr('disabled');
    $("#group_field").attr('disabled','disabled');
  });

  $(".sms_template").click(function(e){
    e.preventDefault();
    data = $(this).data('content');
    $("#sms_content").html(data);
    $("#myModal").modal('hide');
  });


  $('.delete').click(function() {
    var r = confirm("Are you sure!");
    if (r == true){
      $("#formalert").html(loader);
      var action = $(this).attr('href');
      var id = $(this).data('id');
      var url = current_url;
      var form = $(document.createElement('form')).attr('action', action).attr('method','post');
      $('body').append(form);
      $(document.createElement('input')).attr('type', 'hidden').attr('name', 'id').attr('value', id).appendTo(form);
      $(document.createElement('input')).attr('type', 'hidden').attr('name', 'url').attr('value', url).appendTo(form);
      $(form).submit();
    }
    return false;
  });

  $('form.ajax-form').on('submit', function() {
    $("#formalert").html(loader);
    submitBtn = $(this).find('[type=submit]');
    submitBtn.attr('disabled', 'disabled');
    var obj = $(this), 
      url = obj.attr('action'),
      method = obj.attr('method'),
      data = $(this).serialize();;
    $.ajax({
      url: url,
      type: method,
      data: data,
      success: function(response) {
        var result = jQuery.parseJSON( response);
        $("#formalert").html(result.html);
        if(result.status === 'added'){
          $('form.ajax-form')[0].reset();
        }
        submitBtn.removeAttr('disabled', 'disabled');
      }
    });
    return false; 
  });

  $('#select_all').click(function(){
    if($(this).attr('checked')){
      $('.select_one').each(function(){
        $(this).attr('checked','checked');
    });
    }
    else{
      $('.select_one').each(function(){
        $(this).removeAttr('checked');
      });
    }
  });

  $('.select_one').click(function(){
    $('#select_all').removeAttr('checked');
  });

  $('#delete-selected').click(function(e){
    e.preventDefault();
    var ids = new Array();
    var url = current_url;
    $('.select_one').each(function(){
      if($(this).attr('checked')){
        ids.push($(this).val());
      }
    });
    if(ids.length === 0){
      alert('No item selected');
      return false;
    }
    var r = confirm("Are you sure!");
    if (r == true){
      $("#formalert").html(loader);
      var action = $(this).attr('href');
      var form2 = $(document.createElement('form')).attr('action', action).attr('method','post');
      $('body').append(form2);
      $(document.createElement('input')).attr('type', 'hidden').attr('name', 'id').attr('value', ids).appendTo(form2);
      $(document.createElement('input')).attr('type', 'hidden').attr('name', 'url').attr('value', url).appendTo(form2);
      $(form2).submit();
    }    
    return false;
  });


  function preloadImg(src) {
    $('<img/>')[0].src = src;
  }
  preloadImg( site_url +'assets/img/spinner.gif');

})(jQuery);
