pimcore.registerNS("pimcore.plugin.ProductManagerBundle");

pimcore.plugin.ProductManagerBundle = Class.create({

    initialize: function () {
        document.addEventListener(pimcore.events.pimcoreReady, this.pimcoreReady.bind(this));
    },

    pimcoreReady: function (e) {
        // alert("ProductManagerBundle ready!");
    }
});

var ProductManagerBundlePlugin = new pimcore.plugin.ProductManagerBundle();