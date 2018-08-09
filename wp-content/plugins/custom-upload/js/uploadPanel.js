(function($){

  let UploadController = function(){

    let self = this;

    let eventListener;

    showPreloadFileList = function(){
      var input = document.getElementById('uploadInput');
      var output = $('.uc-file-name');
      output.append(input.files.item(0).name);
    }

    self.init = function(listener){
      eventListener = listener;
    }

    self.run = function(){

      $(eventListener).on('change', '#uploadInput', function(){
        showPreloadFileList();
      });
    }
  }

  let Navigator = function(){
    let self = this;

    let eventListener = '#fileTree';

    let target = '#fileTree .uc-list';

    let actualDir;

    let filename;

    let navigate = function(dirName){
      var data = {
        'path': dirName,
        'action': 'cu_navigate',
      }
      $.get(ajaxurl, data, function(data){
        $(target).html(data);
      })
    }

    self.run = function(){

      $(eventListener).off().on('click', '.uc-dir', function(){
        let dirName = $(this).text();
        navigate(dirName);
      });

      $(eventListener).on('click', '#ucGoBack', function(){
        navigate();
      });

    }
  }


  var loadUserContentCallback = function(form, action, target, callback){
    var data = {
      'user': $(form).serialize(),
      'action': action,
    }
    $.post(ajaxurl, data, function(data){
      $(target).html(data);

      if (callback != undefined)
        callback(data);
    })
  }

  var sendContent = function(form, action, target, callback){
    var data = {
      'data': $(form).serialize(),
      'action': action,
    }
    $.post(ajaxurl, data, function(data){
      data = JSON.parse(data);
      if (data['response'] != undefined)
        $(target).html(data['response']);

      if (callback != undefined)
        callback(data);
    })
  }


  $(document).ready(function(){

    let root = '#customUploadPanel';

    $(root).off().on('submit', '#filesByClientForm', function(e){
      let loader = '<i class="fa fa-spinner fa-pulse fa-5x fa-fw" aria-hidden="true"></i>';
      e.preventDefault(); e.stopPropagation();
      $('#filesPermissionTable').html(loader);
      loadUserContentCallback(this, 'load_permission', '#filesPermissionTable');

    });

    $(root).on('submit', '#downloadsByClientForm', function(e){
      let loader = '<i class="fa fa-spinner fa-pulse fa-5x fa-fw" aria-hidden="true"></i>';
      e.preventDefault(); e.stopPropagation();
      $('#filesDownloadsTable').html(loader);
      loadUserContentCallback(this, 'load_history', '#filesDownloadsTable');
    });

    $(root).on('submit', '#newClientForm', function(e){
      let loader = '<i class="fa fa-spinner fa-pulse fa-3x fa-fw" aria-hidden="true"></i>';
      e.preventDefault(); e.stopPropagation();
      $('.cu-loader').html(loader);

      sendContent(this, 'cu_add_client', '.uc-list ul', function(data){
        $('.cu-loader').empty();
        $('#actionResult').removeClass('hidden');
        let msg = '<p>'+data['msg']+'</p>';
        $('#actionResult').removeClass();
        $('#actionResult').addClass(data['type']);
        $('#actionResult').html(msg);
      });
    });

    $(root).on('change', '#sucursalClientSelection select', function(e){
      let loader = '<i class="fa fa-spinner fa-pulse fa-5x fa-fw" aria-hidden="true"></i>';
      $('.cu-loadIndicator').html(loader);
      $('.uc-list ul').empty();
      $('#sucursalInput').val('');

      loadUserContentCallback(this, 'cu_get_sucursales', '.uc-list ul', function(){
        $('.cu-loadIndicator').empty();
      });
    })

    $(root).on('submit', '#uploadSucursalForm', function(e){
      e.preventDefault(); e.stopPropagation();
      let loader = '<i class="fa fa-spinner fa-pulse fa-3x fa-fw" aria-hidden="true"></i>';
      $('.cu-loader').html(loader);

      sendContent(this, 'cu_add_sucursal', '.uc-list ul', function(data){
        $('.cu-loader').empty();
        $('#actionResult').removeClass('hidden');
        let msg = '<p>'+data['msg']+'</p>';
        $('#actionResult').removeClass();
        $('#actionResult').addClass(data['type']);
        $('#actionResult').html(msg);
        $('#sucursalInput').val('');

      });
    })

    $(root).on('submit', '#editFeaturesForm', function(e){
      e.preventDefault(); e.stopPropagation();
      let loader = '<i class="fa fa-spinner fa-pulse fa-3x fa-fw" aria-hidden="true"></i>';
      $('.cu-loader').html(loader);
      sendContent(this, 'cu_edit_features', '#actionResult', function(data){
        $('.cu-loader').empty();
        $('#actionResult').addClass(data['type']);
        $('#actionResult').removeClass('hidden');
      })
    })

    let controller = new UploadController();
    let nav = new Navigator();

    controller.init(root);
    controller.run();
    nav.run();
  })
}
)(jQuery);
