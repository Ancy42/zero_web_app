var route_prefix = "/admin/laravel-filemanager";

// open filemanager
document.querySelectorAll(".lfm").forEach(button => {
    button.addEventListener("click", function (e) {
        e.preventDefault();

        const container = this.closest(".image-container");
        const iframe = document.getElementById("lfmIframe");

        // save references in iframe dataset
        iframe.dataset.containerId = container.dataset.containerId;

        // open filemanager
        iframe.src = route_prefix + "?type=file&callback=SetUrl";
        $("#lfmModal").modal("show");
    });
});

// callback after selecting file(s)
window.SetUrl = function (items) {
    const iframe = document.getElementById("lfmIframe");

    // get the right container
    const container = document.querySelector(
        `.image-container[data-container-id="${iframe.dataset.containerId}"]`
    );

    if (!container) return;

    const target_input = container.querySelector(".thumbnailAdd");
    const target_preview = container.querySelector(".holder");
    const target_thumbnailPath = container.querySelector(".thumbnailPath");

    target_preview.innerHTML = "";

    const files = items.map(item => ({
        name: item.name,
        path: item.url.split("/storage/")[1],
        url: item.url,
    }));


    if (target_thumbnailPath) {
        const parent = target_thumbnailPath.parentNode;

        // remove old hidden inputs (for multiple mode only)
        parent.querySelectorAll('input[data-generated="true"]').forEach(el =>
            el.remove()
        );

        if (target_thumbnailPath.hasAttribute("multiple")) {
            // multiple mode
            const frag = document.createDocumentFragment();

            files.forEach(file => {
                const hidden = document.createElement("input");
                hidden.type = "hidden";
                hidden.name = target_thumbnailPath.name;
                hidden.value = file.path;
                hidden.dataset.generated = "true";
                frag.appendChild(hidden);

                const img = document.createElement("img");
                img.src = file.url;
                target_preview.appendChild(img);
            });

            parent.appendChild(frag);
            target_input.value = files.map(f => f.name).join(",");
        } else {
            // single mode
            if (files[0]) {
                target_thumbnailPath.value = files[0].path;

                const img = document.createElement("img");
                img.src = files[0].url;
                target_preview.appendChild(img);

                target_input.value = files[0].name;
            } else {
                target_input.value = "";
                target_thumbnailPath.value = "";
            }
        }
    }

    target_input.dispatchEvent(new Event("change"));
    target_preview.dispatchEvent(new Event("change"));
    $("#lfmModal").modal("hide");
};


// alias for filemanager fallback
window.lfmSetUrl = window.SetUrl;

