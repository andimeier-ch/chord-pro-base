(function() {
  "use strict";
  function normalizeComponent(scriptExports, render, staticRenderFns, functionalTemplate, injectStyles, scopeId, moduleIdentifier, shadowMode) {
    var options = typeof scriptExports === "function" ? scriptExports.options : scriptExports;
    if (render) {
      options.render = render;
      options.staticRenderFns = staticRenderFns;
      options._compiled = true;
    }
    return {
      exports: scriptExports,
      options
    };
  }
  const _sfc_main = {
    props: {
      label: String,
      value: String
    },
    methods: {
      onInput(value) {
        this.$emit("input", value);
      }
    }
  };
  var _sfc_render = function render() {
    var _vm = this, _c = _vm._self._c;
    return _c("k-field", { attrs: { "label": _vm.label } }, [_c("k-input", { attrs: { "value": _vm.value, "name": "chordProCode", "type": "textarea" }, on: { "input": _vm.onInput } })], 1);
  };
  var _sfc_staticRenderFns = [];
  _sfc_render._withStripped = true;
  var __component__ = /* @__PURE__ */ normalizeComponent(
    _sfc_main,
    _sfc_render,
    _sfc_staticRenderFns
  );
  __component__.options.__file = "/var/www/html/site/plugins/chordpro/src/components/fields/ChordproeditorField.vue";
  const ChordproeditorField = __component__.exports;
  window.panel.plugin("andimeier-ch/chordpro", {
    fields: {
      chordproeditor: ChordproeditorField
    }
  });
})();
