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


  var loadUserContentCallback = function(form, action, target){
    var data = {
      'user': $(form).serialize(),
      'action': action,
    }
    $.post(ajaxurl, data, function(data){
      $(target).html(data);
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


    let controller = new UploadController();
    let nav = new Navigator();

    controller.init(root);
    controller.run();
    nav.run();
  })
}
)(jQuery);
