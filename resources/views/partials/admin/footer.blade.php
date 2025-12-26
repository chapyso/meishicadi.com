<!-- Critical Scripts -->
<script src="{{asset('custom/js/jquery.min.js')}}"></script>
<script src="{{ asset('custom/js/custom.js')}}" ></script>

<!-- Bootstrap and Core -->
<script src="{{ asset('assets/js/plugins/popper.min.js') }}"></script>
<script src="{{ asset('assets/js/plugins/perfect-scrollbar.min.js') }}"></script>
<script src="{{ asset('assets/js/plugins/bootstrap.min.js') }}"></script>
<script src="{{ asset('assets/js/dash.js') }}"></script>

<!-- Optimize loader -->
<script>
// Hide loader faster for better UX
document.addEventListener('DOMContentLoaded', function() {
    const loader = document.getElementById('loader-bg');
    if (loader) {
        setTimeout(function() {
            loader.style.opacity = '0';
            setTimeout(function() {
                loader.style.display = 'none';
            }, 300);
        }, 100);
    }
});

// Fallback: hide loader after 2 seconds max
setTimeout(function() {
    const loader = document.getElementById('loader-bg');
    if (loader) {
        loader.style.opacity = '0';
        setTimeout(function() {
            loader.style.display = 'none';
        }, 300);
    }
}, 2000);
</script>

<!-- Non-critical scripts with async/defer -->
<script src="{{asset('assets/js/pages/wow.min.js')}}" defer></script>
<script src="{{ asset('assets/js/plugins/feather.min.js') }}" defer></script>
<script src="{{asset('custom/libs/summernote/summernote-bs4.js')}}" defer></script>
<script src="{{ asset('custom/libs/bootstrap-notify/bootstrap-notify.min.js')}}" defer></script>

<!-- Charts and Data -->
<script src="{{ asset('assets/js/plugins/apexcharts.min.js')}}" defer></script>
<script src="{{ asset('assets/js/plugins/main.min.js') }}" defer></script>
<script src="{{ asset('assets/js/plugins/choices.min.js') }}" defer></script>
<script src="{{ asset('assets/js/plugins/simple-datatables.js')}}" defer></script>

<!-- UI Components -->
<script src="{{ asset('assets/js/plugins/sweetalert2.all.min.js') }}" defer></script>
<script src="{{ asset('assets/js/pages/ac-alert.js') }}" defer></script>
<script src="{{ asset('assets/js/plugins/simplebar.min.js') }}" defer></script>
<script src="{{ asset('assets/js/plugins/bootstrap-switch-button.min.js') }}" defer></script>

<!-- External Libraries -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/pickadate.js/3.5.3/picker.js" defer></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pickadate.js/3.5.3/picker.date.js" defer></script>

<!-- Custom Scripts -->
<script src="{{ asset('custom/js/emojionearea.min.js')}}" defer></script>
<script src="{{ asset('public/custom/js/socialSharing.js') }}" defer></script>

<script>
  if ($("#pc-dt-simple").length > 0) {
    const dataTable = new simpleDatatables.DataTable("#pc-dt-simple");
  }
</script>

{{-- MODAL --}}
 @stack('custom-scripts')

<script>
  feather.replace();
  var pctoggle = document.querySelector("#pct-toggler");
  if (pctoggle) {
    pctoggle.addEventListener("click", function () {
      if (
        !document.querySelector(".pct-customizer").classList.contains("active")
      ) {
        document.querySelector(".pct-customizer").classList.add("active");
      } else {
        document.querySelector(".pct-customizer").classList.remove("active");
      }
    });
  }

  var themescolors = document.querySelectorAll(".themes-color > a");
  for (var h = 0; h < themescolors.length; h++) {
    var c = themescolors[h];

    c.addEventListener("click", function (event) {
      var targetElement = event.target;
      if (targetElement.tagName == "SPAN") {
        targetElement = targetElement.parentNode;
      }
      var temp = targetElement.getAttribute("data-value");
      removeClassByPrefix(document.querySelector("body"), "theme-");
      document.querySelector("body").classList.add(temp);
    });
  }


  function removeClassByPrefix(node, prefix) {
    for (let i = 0; i < node.classList.length; i++) {
      let value = node.classList[i];
      if (value.startsWith(prefix)) {
        node.classList.remove(value);
      }
    }
  }
</script>
<script>

    function toastrs(title, message, type) {
    var o, i;
    var icon = '';
    var cls = '';
    if (type == 'success') {
        icon = 'fas fa-check-circle';
        // cls = 'success';
        cls = 'primary';
    } else {
        icon = 'fas fa-times-circle';
        cls = 'danger';
    }

    // console.log(type,cls);
    $.notify({ icon: icon, title: " " + title, message: message, url: "" }, {
        element: "body",
        type: cls,
        allow_dismiss: !0,
        placement: {
            from: 'top',
            align: 'right'
        },
        offset: { x: 15, y: 15 },
        spacing: 10,
        z_index: 1080,
        delay: 2500,
        timer: 2000,
        url_target: "_blank",
        mouse_over: !1,
        animate: { enter: o, exit: i },
        // danger
        template: '<div class="toast text-white bg-'+cls+' fade show" role="alert" aria-live="assertive" aria-atomic="true">'
                +'<div class="d-flex">'
                    +'<div class="toast-body"> '+message+' </div>'
                    +'<button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>'
                +'</div>'
            +'</div>'
        // template: '<div class="alert alert-{0} alert-icon alert-group alert-notify" data-notify="container" role="alert"><div class="alert-group-prepend alert-content"><span class="alert-group-icon"><i data-notify="icon"></i></span></div><div class="alert-content"><strong data-notify="title">{1}</strong><div data-notify="message">{2}</div></div><button type="button" class="close" data-notify="dismiss" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>'
    });
}
</script>


