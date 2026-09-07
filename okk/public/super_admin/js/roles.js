(function ($) {
  "use strict";

  // Build global pagination HTML (same design as common-pagination.blade.php)
  function renderGlobalPagination(totalPages, currentPage) {
    if (totalPages <= 1) return "";
    var start = Math.max(1, currentPage - 2);
    var end = Math.min(totalPages, currentPage + 2);
    var html = '<div class="dataTables_paginate paging_simple_numbers">';

    // Prev
    html += '<a class="paginate_button previous ' + (currentPage === 1 ? "disabled" : "ajax-page") + '" data-page="' + (currentPage > 1 ? currentPage - 1 : "") + '" role="link">';
    html += '<i class="fa-solid fa-angles-left"></i></a>';

    // First + dots
    if (start > 1) {
      html += '<a class="paginate_button ajax-page" data-page="1" role="link">1</a>';
      if (start > 2) html += '<span><a class="paginate_button disabled" role="link" aria-disabled="true">...</a></span>';
    }

    // Page numbers
    for (var p = start; p <= end; p++) {
      if (currentPage === p) {
        html += '<span><a class="paginate_button current" aria-current="page" role="link" data-page="' + p + '">' + p + "</a></span>";
      } else {
        html += '<a class="paginate_button ajax-page" data-page="' + p + '" role="link">' + p + "</a>";
      }
    }

    // Last + dots
    if (end < totalPages) {
      if (end < totalPages - 1) html += '<span><a class="paginate_button disabled" role="link" aria-disabled="true">...</a></span>';
      html += '<a class="paginate_button ajax-page" data-page="' + totalPages + '" role="link">' + totalPages + "</a>";
    }

    // Next
    html += '<a class="paginate_button next ' + (currentPage === totalPages ? "disabled" : "ajax-page") + '" data-page="' + (currentPage < totalPages ? currentPage + 1 : "") + '" role="link">';
    html += '<i class="fa-solid fa-angles-right"></i></a>';
    html += "</div>";
    return html;
  }

  $(document).ready(function () {
    var table = $("#rolesDataTable").DataTable({
      pageLength: 8,
      ordering: false,
      serverSide: true,
      processing: true,
      responsive: true,
      searching: true,
      paging: true,
      info: false,
      dom: "t",
      ajax: {
        url: $("#roles-data-route").val()
      },
      language: {
        paginate: {
          previous: "<i class='fa fa-chevron-left'></i>",
          next: "<i class='fa fa-chevron-right'></i>"
        }
      },
      columnDefs: [{ targets: "keep-show", className: "all" }],
      columns: [
        { data: "sl", name: "sl", searchable: false, orderable: false },
        { data: "display_name", name: "display_name" },
        { data: "permissions_count", name: "permissions_count", searchable: false },
        { data: "action", name: "action", searchable: false }
      ],
      drawCallback: function () {
        var info = table.page.info();
        var totalPages = info.pages;
        var currentPage = info.page + 1;
        var $wrap = $("#roles-pagination-wrap");
        $wrap.html(renderGlobalPagination(totalPages, currentPage));
        $wrap.off("click", ".ajax-page").on("click", ".ajax-page", function (e) {
          e.preventDefault();
          var page = $(this).data("page");
          if (page) table.page(page - 1).draw("page");
        });
      }
    });

    $("#searchData").on("keyup", function () {
      table.search(this.value).draw();
    });
  });

  window.deleteItem = function (url, id) {
    Swal.fire({
      title: "Sure! You want to delete?",
      text: "You won't be able to revert this!",
      icon: "warning",
      showCancelButton: true,
      confirmButtonColor: "#3085d6",
      cancelButtonColor: "#d33",
      confirmButtonText: "Yes, Delete It!"
    }).then(function (result) {
      if (result.value) {
        $.ajax({
          type: "POST",
          url: url,
          data: {},
          headers: { "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content") },
          success: function (data) {
            Swal.fire({ title: "Deleted", html: ' <span style="color:red">Item has been deleted</span> ', timer: 2000, icon: "success" });
            toastr.success(data.message);
            if (typeof id !== "undefined" && id && $("#" + id).length && $.fn.DataTable.isDataTable("#" + id)) {
              $("#" + id).DataTable().ajax.reload();
            } else {
              location.reload();
            }
          },
          error: function (error) {
            toastr.error(error.responseJSON && error.responseJSON.message);
          }
        });
      }
    });
  };
})(jQuery);
