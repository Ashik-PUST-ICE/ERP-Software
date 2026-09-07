(function ($) {
  "use strict";
  /*-------------------------------------------
  preloader active
  --------------------------------------------- */
  jQuery(window).on("load", function () {
    // jQuery(".preloader").fadeOut("slow");
    setTimeout(function () {
      jQuery(".preloader").fadeOut("slow");
    }, 200); // 1000 = 1 second delay
  });

  $(window).on("scroll", function () {
    const scrolled = $(this).scrollTop() >= 300;
    $(".header-area").toggleClass("stick", scrolled);
  });

  jQuery(document).ready(function () {
    /*-------------------------------------------
    js scrollup
    --------------------------------------------- */
    $.scrollUp({
      scrollText: '<i class="fa fa-angle-up"></i>',
      easingType: "linear",
      scrollSpeed: 900,
      animation: "fade",
    });
    /*-------------------------------------------
      metisMenu active
    --------------------------------------------- */
    // $("#metismenu").metisMenu();
    /*-------------------------------------------
      background Image active
    --------------------------------------------- */
    $("[data-background]").each(function () {
      $(this).css("background-image", "url(" + $(this).attr("data-background") + ")");
    });
    /*-------------------------------------------
      toggleSidebar active
    --------------------------------------------- */
    function toggleSidebar() {
      $(".sidebar-area").toggleClass("menuClose");
    }
    $(".mobileMenu, .sidebar-overlay").on("click", toggleSidebar);
    /*-------------------------------------------
      niceSelect active
    --------------------------------------------- */
    $('.select').niceSelect();

    /*-------------------------------------------*/
    /* niceSelect re-initialize for modals */
        $(".select2-active").select2({
          minimumResultsForSearch: Infinity,
            // placeholder: "Select Providers",
            // allowClear: true,
        });
    /* Fix for dynamic options loaded in modals */
    /*-------------------------------------------*/
    $(document).on('shown.bs.modal', '.modal', function () {
        // For each select inside the modal that has nice-select
        $(this).find('.select').each(function() {
            // Check if nice-select is already initialized
            if ($(this).next('.nice-select').length) {
                // Destroy existing nice-select and re-create
                $(this).next('.nice-select').remove();
                $(this).removeClass('nice-select-processed');
                $(this).niceSelect();
            }
        });
    });
    /*-------------------------------------------
      DataTable active
    --------------------------------------------- */
    new DataTable('.data-table', {
      responsive: true,
      paging: false,
      searching: false,
      info: false,
      lengthChange: false,
      pageLength: 10,
      ordering: false,
      dom: 'lftip',
      columnDefs: [
        {
          targets: 'keep-show',
          className: 'all'
        }
      ],
      language: {
        paginate: {
          previous: "<i class='fa fa-chevron-left'></i>",
          next: "<i class='fa fa-chevron-right'></i>"
        }
      }
    });
    /*-------------------------------------------
      DataTable active
    --------------------------------------------- */
    const searchTable = new DataTable('.search-datatable', {
      responsive: true,
      paging: false,
      searching: true,
      info: false,
      ordering: false,
      dom: 't',
      columnDefs: [
        {
          targets: 'keep-show',
          className: 'all'
        }
      ]
    });
    $('#searchData').on('keyup', function () {
      searchTable.search(this.value).draw();
    });
    /*-------------------------------------------
      summernote active
    --------------------------------------------- */
    $('.summernote').summernote({
      height: 270,
        toolbar: [
          ['style', ['style']],
          ['font', ['bold', 'italic', 'underline', 'clear']],
          ['fontsize', ['fontsize']],
          ['color', ['color']],
          ['para', ['ul', 'ol', 'paragraph']],
          ['insert', ['picture']], // removed 'video'
          // ['view', ['codeview']] // removed 'fullscreen' & 'help'
      ]
    });

    /*-------------------------------------------
      file-input active
    --------------------------------------------- */
    $(document).on('change', '.file-input', function () {
      let fileName = $(this).val().split('\\').pop();
      let wrapper = $(this).closest('.file-upload');
      if (fileName) {
        wrapper.find('.file-text').text(fileName);
      } else {
        wrapper.find('.file-text').text('Choose image to upload');
      }
    });
    /*-------------------------------------------
      multi-step-form active
    --------------------------------------------- */
    let currentStep = 0;
    const $steps = $('.step');
    const $formSteps = $('.form-step');
    const lastIndex = $steps.length - 1;
    function updateSteps() {
      $formSteps
        .removeClass('active')
        .eq(currentStep)
        .addClass('active');
      $steps.each(function (index) {
        $(this).removeClass('active completed');
        if (index < currentStep) {
          $(this).addClass('completed');
        }
        if (index === currentStep) {
          $(this).addClass('active');
          if (index === lastIndex) {
            $(this).addClass('completed');
          }
        }
      });
    }
    $('.btn-next').on('click', function () {
      if (currentStep < lastIndex) {
        currentStep++;
        updateSteps();
      }
    });
    $('.btn-back').on('click', function () {
      if (currentStep > 0) {
        currentStep--;
        updateSteps();
      }
    });
    /*-------------------------------------------
      FullCalendar active
    --------------------------------------------- */
    $(function () {
      let calendarEl = document.getElementById('calendar');
      if (!calendarEl) return;
      let calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        initialDate: '2026-01-01',
        headerToolbar: false,
        editable: true,
        events: [
          { id: '1', title: 'Quick question for our community...', start: '2026-01-03' },
          { id: '2', title: 'Quick question for our community...', start: '2026-01-05' },
          { id: '3', title: 'Quick question for our community...', start: '2026-01-07' },
          { id: '4', title: 'Quick question for our community...', start: '2026-01-16' },
          { id: '5', title: 'Quick question for our community...', start: '2026-01-21' }
        ],
        eventContent(arg) {
          let wrap = document.createElement('div');
          wrap.className = 'post';
          wrap.innerHTML = `
        <div class="post-head">
          <div class="account-platform">
            <img src="assets/images/profile-image-1.png">
            <span class="platform-icon">
              <i class="fa-brands fa-facebook-f"></i>
            </span>
          </div>
          <span class="menu-btn">⋯</span>
        </div>
        <div class="event-title">${arg.event.title}</div>
        <div class="event-dropdown">
          <div class="edit">Edit</div>
          <div class="publish">Publish Now</div>
          <div class="delete">Delete</div>
        </div>
      `;
          return { domNodes: [wrap] };
        },
        eventDidMount(info) {
          const el = info.el;
          const dropdown = el.querySelector('.event-dropdown');
          const menuBtn = el.querySelector('.menu-btn');

          dropdown.style.display = 'none';

          menuBtn.addEventListener('click', (e) => {
            e.stopPropagation();
            document.querySelectorAll('.event-dropdown').forEach(d => {
              if (d !== dropdown) d.style.display = 'none';
            });
            dropdown.style.display =
              dropdown.style.display === 'block' ? 'none' : 'block';
          });
          el.querySelector('.edit').onclick = () =>
            alert('Edit event ' + info.event.id);

          el.querySelector('.publish').onclick = () =>
            alert('Published event ' + info.event.id);

          el.querySelector('.delete').onclick = () => {
            info.event.remove();
          };
        },
        eventDrop(info) {
          console.log('New date:', info.event.startStr);
        },
        datesSet(info) {
          updateHeader(info.start, info.end, info.view.title);
        }
      });
      calendar.render();
      $('#prevBtn').click(() => calendar.prev());
      $('#nextBtn').click(() => calendar.next());
      function updateHeader(start, end, title) {
        let s = new Date(start);
        let e = new Date(end);
        e.setDate(e.getDate() - 1);

        let opt = { month: 'short', day: 'numeric' };
        $('#rangeText').text(
          s.toLocaleDateString('en-US', opt) + ' - ' +
          e.toLocaleDateString('en-US', opt)
        );
        $('#monthTitle').text(title);
      }
      document.addEventListener('click', () => {
        document.querySelectorAll('.event-dropdown').forEach(d => {
          if (d.isConnected) d.style.display = 'none';
        });
      });
    });

    /*-------------------------------------------
      header search active
    --------------------------------------------- */
    $('.search-btn').on('click', function (e) {
      e.stopPropagation();
      $('.search-area').toggleClass('active');
    });
    $('.search-area').on('click', function (e) {
      e.stopPropagation();
    });
    $(document).on('click', function () {
      $('.search-area').removeClass('active');
    });
    /*-------------------------------------------
      Language active
    --------------------------------------------- */
    $(document).on('click', '.dropdown-item', function (e) {
      let lang = $(this).data('lang');
      let flag = $(this).data('flag');
      if (lang && flag) {
        e.preventDefault();
        $('.selected-text').text(lang);
        $('.language-btn img').attr('src', `assets/images/flag-${flag}.png`);
        $('.dropdown-item').removeClass('active');
        $(this).addClass('active');
      }
    });

    /*-------------------------------------------
      Copy Text active
    --------------------------------------------- */
    $(document).on('click', '.copy-btn', function () {
      const $wrap = $(this).closest('.copy-wrap');
      const $input = $wrap.find('input');
      if (!$input.val()) return;
      const textToCopy = $input.val();
      if (navigator.clipboard) {
        navigator.clipboard.writeText(textToCopy).then(() => {
          showCopied($(this));
        });
      }
      else {
        $input.select();
        document.execCommand('copy');
        showCopied($(this));
      }
    });
    function showCopied($btn) {
      const originalHtml = $btn.html();
      // $btn.text('Copied ✓');
      $btn.text('✓');

      setTimeout(() => {
        $btn.html(originalHtml);
      }, 1500);
    }

  });
})(jQuery);
