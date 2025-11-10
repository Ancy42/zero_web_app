var lfm_route = location.origin + location.pathname;
var show_list;
var sort_type = "alphabetic";
var multi_selection_enabled = false;
var selected = [];
var items = [];
var is_deleting = false;

$.fn.fab = function (options) {
    var menu = this;
    menu.addClass("fab-wrapper");

    var toggler = $("<a>")
        .addClass("fab-button fab-toggle")
        .append($("<i>").addClass("fas fa-plus"))
        .click(function () {
            menu.toggleClass("fab-expand");
        });

    menu.append(toggler);

    options.buttons.forEach(function (button) {
        toggler.before(
            $("<a>")
                .addClass("fab-button fab-action")
                .attr("data-label", button.label)
                .attr("id", button.attrs.id)
                .append($("<i>").addClass(button.icon))
                .click(function () {
                    menu.removeClass("fab-expand");
                })
        );
    });
};

$(document).ready(function () {
    $("#fab").fab({
        buttons: [
            {
                icon: "fas fa-upload",
                label: lang["nav-upload"],
                attrs: { id: "upload" },
            },
            {
                icon: "fas fa-folder",
                label: lang["nav-new"],
                attrs: { id: "add-folder" },
            },
        ],
    });

    actions.reverse().forEach(function (action) {
        $("#nav-buttons > ul").prepend(
            $("<li>")
                .addClass("nav-item")
                .append(
                    $("<a>")
                        .addClass("nav-link d-none")
                        .attr("data-action", action.name)
                        .attr("data-multiple", action.multiple)
                        .append(
                            $("<i>").addClass("fas fa-fw fa-" + action.icon)
                        )
                        .append($("<span>").text(action.label))
                )
        );
    });

    sortings.forEach(function (sort) {
        $("#nav-buttons .dropdown-menu").append(
            $("<a>")
                .addClass("dropdown-item")
                .attr("data-sortby", sort.by)
                .append($("<i>").addClass("fas fa-fw fa-" + sort.icon))
                .append($("<span>").text(sort.label))
                .click(function () {
                    sort_type = sort.by;
                    loadItems();
                })
        );
    });

    loadFolders();
    performLfmRequest("errors").done(function (response) {
        JSON.parse(response).forEach(function (message) {
            $("#alerts").append(
                $("<div>")
                    .addClass("alert alert-warning")
                    .append($("<i>").addClass("fas fa-exclamation-circle"))
                    .append(" " + message)
            );
        });
    });

    $(window).on("dragenter", function () {
        $("#uploadModal").modal("show");
    });

    if (usingWysiwygEditor()) {
        $("#multi_selection_toggle").hide();
    }
});

// ======================
// ==  Navbar actions  ==
// ======================

$("#multi_selection_toggle").click(function () {
    multi_selection_enabled = !multi_selection_enabled;

    $("#multi_selection_toggle i")
        .toggleClass("bi-circle-fill", multi_selection_enabled)
        .toggleClass("bi-circle", !multi_selection_enabled);

    if (!multi_selection_enabled) {
        clearSelected();
    }
});

$("#to-previous").click(function () {
    var previous_dir = getPreviousDir();
    if (previous_dir == "") return;
    goTo(previous_dir);
});

function toggleMobileTree(should_display) {
    if (should_display === undefined) {
        should_display = !$("#tree").hasClass("in");
    }
    $("#tree").toggleClass("in", should_display);
}

$("#show_tree").click(function (e) {
    toggleMobileTree();
});

$("#main").click(function (e) {
    if ($("#tree").hasClass("in")) {
        toggleMobileTree(false);
    }
});

$(document).on("click", "#add-folder", function () {
    dialog(lang["message-name"], "", createFolder);
});

$(document).on("click", "#upload", function () {
    $("#uploadModal").modal("show");
});

$(document).on("click", "[data-display]", function () {
    show_list = $(this).data("display");
    $("[data-display]").removeClass("active-display");
    $(this).addClass("active-display");
    loadItems();
});

$(document).on("click", "[data-action]", function () {
    window[$(this).data("action")](
        $(this).data("multiple") ? getSelectedItems() : getOneSelectedElement()
    );
});


// ==========================
// ==  Multiple Selection  ==
// ==========================

function toggleSelected(e) {
    if (!multi_selection_enabled) {
        selected = [];
    }

    // var sequence = $(e.target).closest("a").data("id");
    var sequence = $(e.currentTarget).data("id"); // use currentTarget

    var element_index = selected.indexOf(sequence);
    if (element_index === -1) {
        selected.push(sequence);
    } else {
        selected.splice(element_index, 1);
    }

    updateSelectedStyle();
}

function clearSelected() {
    selected = [];

    multi_selection_enabled = false;

    updateSelectedStyle();
}

function updateSelectedStyle() {
    items.forEach(function (item, index) {
        $("[data-id=" + index + "]")
            .find(".square")
            .toggleClass("selected", selected.indexOf(index) > -1);
    });
    toggleActions();
}

function getOneSelectedElement(orderOfItem) {
    var index = orderOfItem !== undefined ? orderOfItem : selected[0];
    return items[index];
}

function getSelectedItems() {
    return selected
        .map(function (id) {
            return getOneSelectedElement(id);
        })
        .filter(function (item) {
            return item !== undefined;
        });
}


function renderGridItem(item, index) {
    const $el = $('#item-template-grid')
        .clone()
        .removeAttr('id class')
        .attr('data-id', index);

    $el.find('.item_name').text(item.name);
    $el.find('.item_meta').text(item.meta);

    $('#grid-container').append($el);

    $el.on('click', toggleSelected);
}


function toggleActions() {
    var one_selected = selected.length === 1;
    var many_selected = selected.length >= 1;
    var only_image =
        getSelectedItems().filter(function (item) {
            return !item.is_image;
        }).length === 0;
    var only_file =
        getSelectedItems().filter(function (item) {
            return !item.is_file;
        }).length === 0;

    $("#nav-buttons [data-action=use]").toggleClass("d-none", !(many_selected && only_file));
    $("#nav-buttons [data-action=rename]").toggleClass("d-none", !one_selected);
    $("#nav-buttons [data-action=preview]").toggleClass("d-none", !(many_selected && only_file));
    $("#nav-buttons [data-action=move]").toggleClass("d-none", !many_selected);
    $("#nav-buttons [data-action=download]").toggleClass("d-none", !(many_selected && only_file));
    $("#nav-buttons [data-action=resize]").toggleClass("d-none", !(one_selected && only_image));
    $("#nav-buttons [data-action=crop]").toggleClass("d-none", !(one_selected && only_image));
    $("#nav-buttons [data-action=trash]").toggleClass("d-none", !many_selected);
    $("#nav-buttons [data-action=open]").toggleClass("d-none", !one_selected || only_file);

    $("#multi_selection_toggle").toggleClass(
        "d-none",
        usingWysiwygEditor() || !many_selected
    );
    $("#actions").toggleClass("d-none", selected.length === 0 || !only_image);

    $("#fab").toggleClass("d-none", selected.length !== 0);
}



// ======================
// ==  Folder actions  ==
// ======================

$(document).on("click", "#tree a", function (e) {
    goTo($(e.target).closest("a").data("path"));
    toggleMobileTree(false);
});

function goTo(new_dir) {
    $("#working_dir").val(new_dir);
    loadItems();
}

function getPreviousDir() {
    var working_dir = $("#working_dir").val();
    return working_dir.substring(0, working_dir.lastIndexOf("/"));
}

function setOpenFolders() {
    $("#tree [data-path]").each(function (index, folder) {
        var should_open = ($("#working_dir").val() + "/").startsWith(
            $(folder).data("path") + "/"
        );
        $(folder)
            .children("i")
            .toggleClass("fa-folder-open", should_open)
            .toggleClass("fa-folder", !should_open);
    });

    $("#tree .nav-item").removeClass("active");
    $('#tree [data-path="' + $("#working_dir").val() + '"]')
        .parent(".nav-item")
        .addClass("active");
}

// ====================
// ==  Ajax actions  ==
// ====================

function performLfmRequest(url, parameter, type) {
    var data = defaultParameters();

    if (parameter != null) {
        $.each(parameter, function (key, value) {
            data[key] = value;
        });
    }
    return $.ajax({
        type: "GET",
        beforeSend: function (request) {
            var token = getUrlParam("token");
            if (token !== null) {
                request.setRequestHeader("Authorization", "Bearer " + token);
            }
        },
        dataType: type || "text",
        url: lfm_route + "/" + url,
        data: data,
        cache: false,
    }).fail(function (jqXHR, textStatus, errorThrown) {
        displayErrorResponse(jqXHR);
    });
}

function displayErrorResponse(jqXHR) {
    var message = JSON.parse(jqXHR.responseText);
    if (Array.isArray(message)) {
        message = message.join("<br>");
    }
    notify(
        '<div style="max-height:50vh;overflow: auto;">' + message + "</div>"
    );
}

var refreshFoldersAndItems = function (data) {
    loadFolders();
    if (data != "OK") {
        data = Array.isArray(data) ? data.join("<br/>") : data;
        notify(data);
    }
};

var hideNavAndShowEditor = function (data) {
    $("#nav-buttons > ul").addClass("d-none");
    $("#content").html(data);
    $("#pagination").removeClass("preserve_actions_space");
    $(".paginationBox").addClass("custom-hide");
    clearSelected();
};

function loadFolders() {
    performLfmRequest("folders", {}, "html").done(function (data) {
        $("#tree").html(data);
        loadItems();
    });
}

function generatePaginationHTML(el, args) {
    var template = '<li class="page-item"></li>';
    var linkTemplate = '<a class="page-link"></a>';
    var currentPage = args.currentPage;
    var totalPage = args.totalPage;
    var rangeStart = args.rangeStart;
    var rangeEnd = args.rangeEnd;
    var i;

    if (args.pageRange === null) {
        for (i = 1; i <= totalPage; i++) {
            var button = $(template)
                .attr("data-num", i)
                .append($(linkTemplate).html(i));
            if (i == currentPage) {
                button.addClass("active");
            }
            el.append(button);
        }

        return;
    }

    if (rangeStart <= 3) {
        for (i = 1; i < rangeStart; i++) {
            var button = $(template)
                .attr("data-num", i)
                .append($(linkTemplate).html(i));
            if (i == currentPage) {
                button.addClass("active");
            }
            el.append(button);
        }
    } else {
        var button = $(template)
            .attr("data-num", 1)
            .append($(linkTemplate).html(1));
        el.append(button);

        var button = $(template)
            .addClass("disabled")
            .append($(linkTemplate).html("..."));
        el.append(button);
    }

    for (i = rangeStart; i <= rangeEnd; i++) {
        var button = $(template)
            .attr("data-num", i)
            .append($(linkTemplate).html(i));
        if (i == currentPage) {
            button.addClass("active");
        }
        el.append(button);
    }

    if (rangeEnd >= totalPage - 2) {
        for (i = rangeEnd + 1; i <= totalPage; i++) {
            var button = $(template)
                .attr("data-num", i)
                .append($(linkTemplate).html(i));
            el.append(button);
        }
    } else {
        var button = $(template)
            .addClass("disabled")
            .append($(linkTemplate).html("..."));
        el.append(button);

        var button = $(template)
            .attr("data-num", totalPage)
            .append($(linkTemplate).html(totalPage));
        el.append(button);
    }
}






function createPagination(paginationSetting) {
    var el = $('<ul class="pagination" role="navigation"></ul>');

    var currentPage = paginationSetting.current_page;
    var pageRange = 5;
    var totalPage = Math.ceil(
        paginationSetting.total / paginationSetting.per_page
    );

    var rangeStart = currentPage - pageRange;
    var rangeEnd = currentPage + pageRange;

    if (rangeEnd > totalPage) {
        rangeEnd = totalPage;
        rangeStart = totalPage - pageRange * 2;
        rangeStart = rangeStart < 1 ? 1 : rangeStart;
    }

    if (rangeStart <= 1) {
        rangeStart = 1;
        rangeEnd = Math.min(pageRange * 2 + 1, totalPage);
    }

    generatePaginationHTML(el, {
        totalPage: totalPage,
        currentPage: currentPage,
        pageRange: pageRange,
        rangeStart: rangeStart,
        rangeEnd: rangeEnd,
    });

    $("#pagination").append(el);
}

function loadItems(page) {
    loading(true);
    performLfmRequest(
        "jsonitems",
        { show_list: show_list, sort_type: sort_type, page: page || 1 },
        "html"
    ).done(function (data) {
        selected = [];
        var response = JSON.parse(data);
        var working_dir = response.working_dir;
        items = response.items;

        var hasItems = items.length !== 0;
        var hasPaginator = !!response.paginator;

        $("#empty").toggleClass("d-none", hasItems);
        $("#content").html("").removeAttr("class");
        $("#pagination").html("").removeAttr("class");

        if (hasItems) {
            $("#content").addClass(response.display === "list" ? "list" : "grid");
            $("#pagination").addClass("preserve_actions_space");
             $(".paginationBox").removeClass("custom-hide");

            items.forEach(function (item, index) {
                var template;

                if (response.display === "grid") {
                    template = $("#item-template-grid .gridCart").clone();

                    if (item.is_file) {
                        template
                            .find(".image-container")
                            .removeClass("d-none")
                            .find("img")
                            .attr(
                                "src",
                                item.url
                                    ? item.url + "?timestamp=" + item.time
                                    : "/vendor/laravel-filemanager/img/file.svg"
                            )
                            .attr("alt", item.name);
                    } else {
                        template
                            .find(".folder-icon-container")
                            .removeClass("d-none")
                            .find("img")
                            .attr("alt", item.name);


                    }

                    template.find(".item_name").text(item.name);
                    template
                        .find("time")
                        .text(new Date(item.time * 1000).toLocaleString());
                } else {
                    // clone list template
                    template = $("#item-template-list")
                        .clone()
                        .removeAttr("id")
                        .removeClass("d-none");

                    template.find(".item_name").text(item.name);
                    template
                        .find("time")
                        .text(new Date(item.time * 1000).toLocaleString());

                    if (item.is_file) {
                        template
                            .find(".square")
                            .css(
                                "background-image",
                                'url("' +
                                (item.url
                                    ? item.url +
                                    "?timestamp=" +
                                    item.time
                                    : "/vendor/laravel-filemanager/img/file.svg") +
                                '")'
                            );
                    } else {
                        template
                            .find(".square")
                            .css(
                                "background-image",
                                'url("/vendor/laravel-filemanager/img/folder.svg")'
                            );
                    }
                }
                // fetch folder item count dynamically
                if (!item.is_file) {
                    var folderPath = working_dir + "/" + item.name;
                    performLfmRequest(
                        "jsonitems",
                        { working_dir: folderPath },
                        "html"
                    ).done(function (folderData) {
                        var folderResponse = JSON.parse(folderData);
                        template
                            .find(".item_meta")
                            .text(folderResponse.items.length + " Items");
                    });
                }

                var dropdownMenu = template.find(".action-menu");
                dropdownMenu.html(""); // clear first

                actions.forEach(function (action) {

                    //  If it's a folder, only allow rename + trash
                    if (!item.is_file && !["rename", "trash"].includes(action.name)) {
                        return;
                    }

                    var li = $("<li>").append(
                        $("<a>")
                            .attr("href", "#")
                            .addClass("dropdown-item")
                            .attr("href", "#")
                            .append($("<i>").addClass("fas fa-" + action.icon + " mr-2"))
                            .append(action.label)
                            .click((function (currentItem) {
                                return function (e) {
                                    e.preventDefault();
                                    window[action.name](
                                        action.multiple ? getSelectedItems() : currentItem
                                    );
                                };
                            })(item))
                    );
                    dropdownMenu.append(li);

                });

                template
                    .attr("data-id", index)
                    .click(toggleSelected)
                    .dblclick(function () {
                        if (item.is_file) {
                            use(getSelectedItems());
                        } else {
                            goTo(item.url);
                        }
                    });

                $("#content").append(template);

            });
        }

        // Pagination
        if (hasPaginator) {
            var totalPages = Math.ceil(response.paginator.total / response.paginator.per_page);
            if (totalPages > 1) {
                $("#pagination").show();
                createPagination(response.paginator);

                $("#pagination a").on("click", function (event) {
                    event.preventDefault();
                    loadItems($(this).closest("li")[0].getAttribute("data-num"));
                    return false;
                });
                $("#total-items").text(
                    `Showing ${response.paginator.per_page} items per page, total ${response.paginator.total}`
                );
            } else {
                $("#pagination").hide();
                $("#total-items").text(
                    `Showing total ${response.paginator.total}`
                );
            }


        }
        $("#working_dir").val(working_dir);
        var breadcrumbs = [];
        var validSegments = working_dir.split('/').filter(function (e) { return e; });
        validSegments.forEach(function (segment, index) {
            if (index === 0) {
                // set root folder name as the first breadcrumb
                breadcrumbs.push($("[data-path='/" + segment + "']").text());
            } else {
                breadcrumbs.push(segment);
            }
        });

        $('#current_folder').text(breadcrumbs[breadcrumbs.length - 1]);
        $('#breadcrumbs > ol').html('');
        breadcrumbs.forEach(function (breadcrumb, index) {
            var li = $('<li>').addClass('breadcrumb-item').text(breadcrumb);

            if (index === breadcrumbs.length - 1) {
                li.addClass('active').attr('aria-current', 'page');
            } else {
                li.click(function () {
                    // go to corresponding path
                    goTo('/' + validSegments.slice(0, 1 + index).join('/'));
                });
            }

            $('#breadcrumbs > ol').append(li);
        });
        var atRootFolder = getPreviousDir() == '';
        $('#to-previous').toggleClass('d-none invisible-lg', atRootFolder);
        $('#show_tree').toggleClass('d-none', !atRootFolder).toggleClass('d-block', atRootFolder);
        $('#nav-buttons > ul').removeClass('d-none');

        setOpenFolders();
        loading(false);
        toggleActions();
    });
}



function loading(show_loading) {
    $("#loading").toggleClass("d-none", !show_loading);
}

function createFolder(folder_name) {
    performLfmRequest("newfolder", { name: folder_name }).done(
        refreshFoldersAndItems
    );
}

// ==================================
// ==         File Actions         ==
// ==================================

function rename(item) {
    dialog(lang["message-rename"], item.name, function (new_name) {
        performLfmRequest("rename", {
            file: item.name,
            new_name: new_name,
        }).done(refreshFoldersAndItems);
    });
}


function trash() {
    confirm(lang['message-delete'], function () {
        if (window.is_deleting) return;

        window.is_deleting = true;
        $('#confirm-button-yes').prop('disabled', true).addClass('disabled');

        var freshItems = getSelectedItems();

        if (!freshItems.length) {
            window.is_deleting = false;
            $('#confirm-button-yes').prop('disabled', false).removeClass('disabled');
            $('#confirm').modal('hide');
            return;
        }

        performLfmRequest('delete', {
            items: freshItems.map(function (item) {
                return item.name;
            })
        }).done(function (response) {
            window.is_deleting = false;
            $('#confirm-button-yes').prop('disabled', false).removeClass('disabled');

            // hide modal
            $('#confirm').modal('hide');

            refreshFoldersAndItems(response);

            // clear selection
            selected = [];
            $('.square.selected').removeClass('selected');
            updateSelectedStyle();
        }).fail(function (xhr) {
            console.error('Delete failed', xhr);
            window.is_deleting = false;
            $('#confirm-button-yes').prop('disabled', false).removeClass('disabled');
            $('#confirm').modal('hide');
        });
    });
}


function crop(item) {
    performLfmRequest("crop", { img: item.name }).done(hideNavAndShowEditor);
}

function resize(item) {
    performLfmRequest("resize", { img: item.name }).done(hideNavAndShowEditor);
}


function download(item) {
    var data = defaultParameters();

    data["file"] = item.name;

    var token = getUrlParam("token");
    if (token) {
        data["token"] = token;
    }
    location.href = lfm_route + "/download?" + $.param(data);
}


function open(item) {
    goTo(item.url);
}

function preview(items) {
    var carousel = $("#carouselTemplate")
        .clone()
        .attr("id", "previewCarousel")
        .removeClass("d-none");
    var imageTemplate = carousel
        .find(".carousel-item")
        .clone()
        .removeClass("active");
    var indicatorTemplate = carousel
        .find(".carousel-indicators > li")
        .clone()
        .removeClass("active");
    carousel.children(".carousel-inner").html("");
    carousel.children(".carousel-indicators").html("");
    carousel
        .children(
            ".carousel-indicators,.carousel-control-prev,.carousel-control-next"
        )
        .toggle(items.length > 1);

    items.forEach(function (item, index) {
        var carouselItem = imageTemplate
            .clone()
            .addClass(index === 0 ? "active" : "");

        if (item.thumb_url) {
            carouselItem
                .find(".carousel-image")
                .css(
                    "background-image",
                    "url('" + item.url + "?timestamp=" + item.time + "')"
                );
        } else {
            carouselItem
                .find(".carousel-image")
                .css("width", "50vh")
                .append($("<div>").addClass("mime-icon ico-" + item.icon));
        }

        carouselItem
            .find(".carousel-label")
            .attr("target", "_blank")
            .attr("href", item.url)
            .text(item.name)
            .append($('<i class="fas fa-external-link-alt ml-2"></i>'));

        carousel.children(".carousel-inner").append(carouselItem);

        var carouselIndicator = indicatorTemplate
            .clone()
            .addClass(index === 0 ? "active" : "")
            .attr("data-slide-to", index);
        carousel.children(".carousel-indicators").append(carouselIndicator);
    });

    // carousel swipe control
    var touchStartX = null;

    carousel
        .on("touchstart", function (event) {
            var e = event.originalEvent;
            if (e.touches.length == 1) {
                var touch = e.touches[0];
                touchStartX = touch.pageX;
            }
        })
        .on("touchmove", function (event) {
            var e = event.originalEvent;
            if (touchStartX != null) {
                var touchCurrentX = e.changedTouches[0].pageX;
                if (touchCurrentX - touchStartX > 60) {
                    touchStartX = null;
                    carousel.carousel("prev");
                } else if (touchStartX - touchCurrentX > 60) {
                    touchStartX = null;
                    carousel.carousel("next");
                }
            }
        })
        .on("touchend", function () {
            touchStartX = null;
        });
    // end carousel swipe control

    notify(carousel);
}

function move(items) {
    performLfmRequest("move", {
        items: items.map(function (item) {
            return item.name;
        }),
    }).done(refreshFoldersAndItems);
}

function getUrlParam(paramName) {
    var reParam = new RegExp("(?:[?&]|&)" + paramName + "=([^&]+)", "i");
    var match = window.location.search.match(reParam);
    return match && match.length > 1 ? match[1] : null;
}

function use(items) {
    function useTinymce3(url) {
        if (!usingTinymce3()) {
            return;
        }

        var win = tinyMCEPopup.getWindowArg("window");
        win.document.getElementById(tinyMCEPopup.getWindowArg("input")).value =
            url;
        if (typeof win.ImageDialog != "undefined") {
            // Update image dimensions
            if (win.ImageDialog.getImageData) {
                win.ImageDialog.getImageData();
            }

            // Preview if necessary
            if (win.ImageDialog.showPreviewImage) {
                win.ImageDialog.showPreviewImage(url);
            }
        }
        tinyMCEPopup.close();
    }

    function useTinymce4AndColorbox(url) {
        if (!usingTinymce4AndColorbox()) {
            return;
        }

        parent.document.getElementById(getUrlParam("field_name")).value = url;

        if (typeof parent.tinyMCE !== "undefined") {
            parent.tinyMCE.activeEditor.windowManager.close();
        }
        if (typeof parent.$.fn.colorbox !== "undefined") {
            parent.$.fn.colorbox.close();
        }
    }

    function useTinymce5(url) {
        if (!usingTinymce5()) {
            return;
        }

        parent.postMessage({
            mceAction: "insert",
            content: url,
        });

        parent.postMessage({ mceAction: "close" });
    }

    function useCkeditor3(url) {
        if (!usingCkeditor3()) {
            return;
        }

        if (window.opener) {
            // Popup
            window.opener.CKEDITOR.tools.callFunction(
                getUrlParam("CKEditorFuncNum"),
                url
            );
        } else {
            // Modal (in iframe)
            parent.CKEDITOR.tools.callFunction(
                getUrlParam("CKEditorFuncNum"),
                url
            );
            parent.CKEDITOR.tools.callFunction(
                getUrlParam("CKEditorCleanUpFuncNum")
            );
        }
    }

    function useFckeditor2(url) {
        if (!usingFckeditor2()) {
            return;
        }

        var p = url;
        var w = data["Properties"]["Width"];
        var h = data["Properties"]["Height"];
        window.opener.SetUrl(p, w, h);
    }

    var url = items[0].url;
    var callback = getUrlParam("callback");
    var useFileSucceeded = true;

    if (usingWysiwygEditor()) {
        useTinymce3(url);

        useTinymce4AndColorbox(url);

        useTinymce5(url);

        useCkeditor3(url);

        useFckeditor2(url);
    } else if (callback && window[callback]) {
        window[callback](getSelectedItems());
    } else if (callback && parent[callback]) {
        parent[callback](getSelectedItems());
    } else if (window.opener) {
        window.lfmSetUrl(selected);
    } else if (typeof parent.lfmSetUrl === "function") {
        parent.lfmSetUrl(getSelectedItems());
        $("#lfmModal").modal("hide");
    } else {
        useFileSucceeded = false;
    }

    if (useFileSucceeded) {
        var selectedItems = getSelectedItems();
        if (typeof parent.lfmSetUrl === "function") {
            parent.lfmSetUrl(selectedItems);
            $("#lfmModal").modal("hide");
        } else {
            useFileSucceeded = false;
        }
    } else {
        window.open(url);
    }
}





//end useFile

// ==================================
// ==     WYSIWYG Editors Check    ==
// ==================================


function usingTinymce3() {
    return !!window.tinyMCEPopup;
}

function usingTinymce4AndColorbox() {
    return !!getUrlParam("field_name");
}

function usingTinymce5() {
    return !!getUrlParam("editor");
}

function usingCkeditor3() {
    return !!getUrlParam("CKEditor") || !!getUrlParam("CKEditorCleanUpFuncNum");
}

function usingFckeditor2() {
    return (
        window.opener &&
        typeof data != "undefined" &&
        data["Properties"]["Width"] != ""
    );
}

function usingWysiwygEditor() {
    return (
        usingTinymce3() ||
        usingTinymce4AndColorbox() ||
        usingTinymce5() ||
        usingCkeditor3() ||
        usingFckeditor2()
    );
}

// ==================================
// ==            Others            ==
// ==================================

function defaultParameters() {
    return {
        working_dir: $("#working_dir").val(),
        type: $("#type").val(),
    };
}

function notImp() {
    notify("Not yet implemented!");
}

function notify(body) {
    $("#notify").modal("show").find(".modal-body").html(body);
}

function confirm(body, callback) {
    $("#confirm")
        .find(".btn-primary")
        .toggle(callback !== undefined);
    $("#confirm").find(".btn-primary").click(callback);
    $("#confirm").modal("show").find(".modal-body").html(body);
}

function dialog(title, value, callback) {
    $("#dialog").find("input").val(value);
    $("#dialog").on("shown.bs.modal", function () {
        $("#dialog").find("input").focus();
    });
    $("#dialog")
        .find(".btn-primary")
        .unbind()
        .click(function (e) {
            callback($("#dialog").find("input").val());
        });
    $("#dialog").modal("show").find(".modal-title").text(title);
}

