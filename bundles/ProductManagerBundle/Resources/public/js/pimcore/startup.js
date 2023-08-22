pimcore.registerNS("pimcore.plugin.ProductManagerBundle");

pimcore.plugin.ProductManagerBundle = Class.create(pimcore.plugin.admin, {
    getClassName: function () {
        return "pimcore.plugin.ProductManagerBundle";
    },

    initialize: function () {
        pimcore.plugin.broker.registerPlugin(this);
    },

    pimcoreReady: function (params, broker) {
        // alert("ProductManagerBundle ready!");
    }
});

var ProductManagerBundlePlugin = new pimcore.plugin.ProductManagerBundle();
