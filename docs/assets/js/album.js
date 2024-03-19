$(document).ready(function() {

    const fileSelect = document.getElementById("fileSelect");
    const draggableFile = document.getElementById("draggableFile");
    const result = document.getElementById("result");
    const wrapper = document.getElementById("wrapper");
    const xhr = new XMLHttpRequest();
  
    if (window.File && window.FileList && window.FileReader) {
      function setupReader(file, handler) {
        const reader = new FileReader();
        reader.onloadend = handler;
        reader.readAsDataURL(file);
      }
  
      function overFile(e) {
        e.stopPropagation();
        e.preventDefault();
        wrapper.className = "visible";
      }
  
      function endFile(e) {
        e.stopPropagation();
        e.preventDefault();
        wrapper.className = "";
      }
  
      function dropFile(e) {
        e.stopPropagation();
        e.preventDefault();
        const files = e.target.files || e.dataTransfer.files;
        for (let i = 0; i < files.length; i++) {
          const eachFile = files[i];
          const type = eachFile.type == "" || eachFile.type == null ?
            eachFile.name.split(".")[1] :
            eachFile.type;
          setupReader(eachFile, function(e) {
            $("#result").append('<div class="item"><i class="fa fa-file-o" aria-hidden="true"></i><p>' + eachFile.name + '</p></div>');
          });
        }
        result.style.display = "block";
        wrapper.className = "";
        $(".input-select-wrap").css({
          bottom: "30px",
          top: "inherit",
          transform: "none"
        });
  
        setTimeout(function() {
          $(".item").draggable({
            revert: true,
            start: function() {
              $(".folder").css({
                "background-color": "rgba(0,0,0,0.05)"
              });
              $(this).css({
                display: "inline-block"
              });
            },
            stop: function() {
              $(".folder").css({
                "background-color": "rgba(0,0,0,0)"
              });
              $(this).css({
                display: "block"
              });
            },
            drag: function(event, ui) {
              $(this).css("z-index", "999");
            }
          });
        }, 300);
      }
  
      if (xhr.upload) {
        wrapper.addEventListener("dragover", overFile);
        wrapper.addEventListener("dragenter", overFile);
        draggableFile.addEventListener("dragleave", endFile);
        draggableFile.addEventListener("drop", dropFile);
        fileSelect.addEventListener("change", dropFile);
      }
    }
  
    var targetFolder;
    var folderID;
    var zIndex;
  
    $(".folder").droppable({
      accept: ".item, .file",
      over: function(event, ui) {
        $(this).find("i.fa-file").css({
          transform: "scale(1.1)",
          opacity: "0.5"
        });
        $(this).find("p").css({
          opacity: "0.5"
        });
        $(this).css({
          background: "rgba(0,0,0,0.0)",
          border: "1px solid rgba(0,0,0,0.1)"
        });
        targetFolder = $(".ui-droppable-hover").attr("id") + "-content";
        folderID = $(".ui-droppable-hover").attr("id");
      },
      out: function(event, ui) {
        $(this).find("i.fa-file").css({
          transform: "scale(1)",
          opacity: "1"
        });
        $(this).find("p").css({
          opacity: "1"
        });
        $(this).css({
          background: "rgba(0,0,0,0.05)",
          border: "1px solid rgba(0,0,0,0)"
        });
      },
      drop: function() {
        dropItemToFolder(targetFolder, folderID);
        updateFilesNumbers();
      }
    });
  
    $(".left").sortable({
      revert: true
    });
  
    $(".folder-content").droppable({
      drop: function() {
        const eLtarget = $(this).attr("id");
        const eLFolder = $(this).attr("id").slice(0, -8);
        if (!$(".ui-draggable-dragging").hasClass(eLtarget + "-item")) {
          dropItemToFolder(eLtarget, eLFolder);
          updateFilesNumbers();
        }
      }
    });
  
    let eLfolderindex;
    const draggieWindow = $(".folder-content").draggabilly();
    draggieWindow.on("dragStart", function(event, pointer) {
      zIndex = $(".folder-content")
        .map(function() {
          return $(this).css("z-index");
        })
        .get(), currentzIndex = Math.max.apply(null, zIndex);
      $(this).css({
        display: "block",
        "z-index": currentzIndex + 1
      });
    });
    $(".folder-content").resizable({
      minWidth: 250,
      minHeight: 150,
      start: function(event, ui) {
        $(".folder-content").draggabilly("disable");
      },
      stop: function(event, ui) {
        $(".folder-content").draggabilly("enable");
      }
    });
  
    $(".top-droppable").topDroppable({
      drop: function(e, ui) {
        console.log("dropped into " + $(this).attr('id'));
      }
    });
  
    $(".close-folder-content").click(function() {
      const eLfolder = $(this).parent();
      eLfolder.addClass("easeout2").addClass("closed");
      setTimeout(function() {
        eLfolder.css("display", "none");
      }, 300);
    });
  
    $(".folder").dblclick(function() {
      zIndex = $(".folder-content")
        .map(function() {
          return $(this).css("z-index");
        })
        .get(), currentzIndex = Math.max.apply(null, zIndex);
  
      const eLfolder = $(this).attr("id");
  
      $("#" + eLfolder + "-content").css({
        display: "block",
        "z-index": currentzIndex + 1
      });
      setTimeout(function() {
        $("#" + eLfolder + "-content").addClass("easeout2").removeClass("closed");
      }, 5);
      setTimeout(function() {
        $("#" + eLfolder + "-content").removeClass("easeout2");
      }, 300);
    });
  
    toolTiper();
  });
  
  function toolTiper() {
    $(".tooltiper").each(function() {
      const eLcontent = $(this).attr("data-tooltip");
      const eLtop = $(this).position().top;
      const eLleft = $(this).position().left;
      $(this).append('<span class="tooltip">' + eLcontent + "</span>");
    });
  }
  
  function dragTheFiles() {
    setTimeout(function() {
      $(".file").draggable({
        revert: true,
        start: function() {
          $(".folder-content").draggabilly("disable");
          $(".folder").css({
            "background-color": "rgba(0,0,0,0.05)"
          });
          $(this).css({
            "background-color": "rgba(0,0,0,0.02)"
        });
      },
      stop: function() {
        $(".folder-content").draggabilly("enable");
        $(".folder").css({
          "background-color": "rgba(0,0,0,0)"
        });
        $(this).css({
          "background-color": "rgba(0,0,0,0.0)"
        });
      },
      drag: function(event, ui) {
        $(this).css({
          "z-index": "999"
        });
      }
    });
  }, 300);
}

function dropItemToFolder(target, folderid) {
  $(".ui-draggable-dragging").draggable({
    revert: false
  });
  $(".ui-draggable-dragging").addClass("is-dropped");
  $("#" + folderid).addClass("item-dropped");
  $(".folder").css({
    background: "rgba(0,0,0,0.05)",
    border: "1px solid rgba(0,0,0,0)"
  });
  $(".folder .fa-folder").css({
    transform: "scale(1)",
    opacity: "1"
  });
  $(".folder p").css({
    opacity: "1"
  });
  setTimeout(function() {
    $(".is-dropped").appendTo("#" + target);
    $(".is-dropped").removeClass().addClass(target + "-item").addClass("file");
    $("." + target + "-item").draggable("destroy");
    $("." + target + "-item").css({
      left: "0",
      top: "0"
    });
  }, 300);
  setTimeout(function() {
    $("#" + folderid).removeClass("item-dropped");
  }, 1000);
  dragTheFiles();
}


function updateFilesNumbers() {
  setTimeout(function(){
    $('.folder-content').each(function(){
      const eLFolder = $(this).attr("id").slice(0, -8);
      const filesCount = $(this).find('.file').length;
      $('#'+eLFolder).find('.tooltip').html(filesCount+' file(s)');
    });
  },300);
};

  