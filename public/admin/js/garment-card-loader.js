(function () {
    "use strict";

    function setLoading(card, loading) {
        card.classList.toggle("is-loading", loading);
        card.setAttribute("aria-busy", loading ? "true" : "false");
    }

    function setError(container, url) {
        container.classList.add("is-error");
        container.querySelectorAll("[data-card-key]").forEach(function (card) {
            var value = card.querySelector("[data-card-value]");
            if (value) value.textContent = "-";
        });
        var retry = container.querySelector("[data-card-retry]");
        if (retry) {
            retry.hidden = false;
            retry.onclick = function () {
                retry.hidden = true;
                loadCards(container, url);
            };
        }
    }

    function loadCards(container, url) {
        container.classList.remove("is-error");
        var cards = container.querySelectorAll("[data-card-key]");
        cards.forEach(function (card) { setLoading(card, true); });

        fetch(url, {
            headers: { "Accept": "application/json", "X-Requested-With": "XMLHttpRequest" },
            credentials: "same-origin"
        })
            .then(function (response) {
                if (!response.ok) throw new Error("Card data request failed with status " + response.status);
                return response.json();
            })
            .then(function (payload) {
                if (!payload || typeof payload.cards !== "object") {
                    throw new Error("Card data response is invalid");
                }
                cards.forEach(function (card) {
                    var value = card.querySelector("[data-card-value]");
                    var key = card.getAttribute("data-card-key");
                    if (value && Object.prototype.hasOwnProperty.call(payload.cards, key)) {
                        var cardValue = payload.cards[key];
                        value.textContent = typeof cardValue === "number"
                            ? cardValue.toLocaleString(undefined, { maximumFractionDigits: 1 })
                            : cardValue;
                    }
                    setLoading(card, false);
                });
            })
            .catch(function () {
                cards.forEach(function (card) { setLoading(card, false); });
                setError(container, url);
            });
    }

    document.addEventListener("DOMContentLoaded", function () {
        document.querySelectorAll("[data-card-data-url]").forEach(function (container) {
            loadCards(container, container.getAttribute("data-card-data-url"));
        });
    });
}());
