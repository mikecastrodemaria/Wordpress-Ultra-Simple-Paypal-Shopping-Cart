(() => {
  "use strict";
  var e,
    a = {
      794: () => {
        const e = window.React,
          a = window.wp.blocks,
          t = window.wp.i18n,
          r = window.wp.blockEditor,
          l = window.wp.components;
        function n(e) {
          let a = "";
          for (let t = 0; t < e.length; t++) {
            if ("<" === e[t]) for (; ">" !== e[t] && t < e.length; ) t++;
            [
              "a",
              "b",
              "c",
              "d",
              "e",
              "f",
              "g",
              "h",
              "i",
              "j",
              "k",
              "l",
              "m",
              "n",
              "o",
              "p",
              "q",
              "r",
              "s",
              "t",
              "u",
              "v",
              "w",
              "x",
              "y",
              "z",
              "A",
              "B",
              "C",
              "D",
              "E",
              "F",
              "G",
              "H",
              "I",
              "J",
              "K",
              "L",
              "M",
              "N",
              "O",
              "P",
              "Q",
              "R",
              "S",
              "T",
              "U",
              "V",
              "W",
              "X",
              "Y",
              "Z",
              "1",
              "2",
              "3",
              "4",
              "5",
              "6",
              "7",
              "8",
              "9",
              "0",
              "é",
              "è",
              ",",
              "ç",
              "à",
              "ù",
              ".",
              "ô",
              "î",
              "ê",
              "û",
              "ö",
              "ï",
              "ë",
              "ü",
              "â",
              "ä",
              "€",
              "$",
              "£",
              "¥",
              "%",
              "&",
              "Â",
              "Ä",
              "Ê",
              "Ë",
              "Î",
              "Ï",
              "Ö",
              "Ô",
              "Û",
              "Ü",
              "Ù",
              "À",
              "Ç",
              "É",
              "È",
              "Æ",
              "Œ",
              "œ",
              "Å",
              "Ø",
              "Þ",
              "ð",
              "Ý",
              "ý",
              "þ",
              "ÿ",
              "ß",
              "Ÿ",
              "Š",
              "š",
              "Ž",
              "ž",
              " ",
            ].includes(e[t]) && (a += e[t]);
          }
          return a;
        }
        const o = JSON.parse(
            '{"UU":"create-block/shipping-variation-add-cart-block"}'
          ),
          i = (0, e.createElement)(
            "svg",
            {
              viewBox: "0 0 24 24",
              xmlns: "http://www.w3.org/2000/svg",
              "aria-hidden": "true",
              focusable: "false",
            },
            (0, e.createElement)("path", {
              d: "M4.75 4a.75.75 0 0 0-.75.75v7.826c0 .2.08.39.22.53l6.72 6.716a2.313 2.313 0 0 0 3.276-.001l5.61-5.611-.531-.53.532.528a2.315 2.315 0 0 0 0-3.264L13.104 4.22a.75.75 0 0 0-.53-.22H4.75ZM19 12.576a.815.815 0 0 1-.236.574l-5.61 5.611a.814.814 0 0 1-1.153 0L5.5 12.264V5.5h6.763l6.5 6.502a.816.816 0 0 1 .237.574ZM8.75 9.75a1 1 0 1 0 0-2 1 1 0 0 0 0 2Z",
            })
          );
        (0, a.registerBlockType)(o.UU, {
          icon: i,
          edit: function ({ attributes: a, setAttributes: o }) {
            const {
                productName: i,
                variationNumber: p,
                variationName: s,
                variations: c,
                shippingNumber: m,
                shippings: h,
              } = a,
              u = [],
              v = [];
            if (!c) {
              let e = [];
              if (p > 0) for (let a = 0; a < p; a++) e.push(["", ""]);
              o({ variations: e });
            }
            if (c && c.length < p && p > 0) {
              let e = [];
              for (Array.isArray(c) && (e = c); e.length < p; )
                e.push(["", ""]);
              o({ variations: e });
            }
            if (c && c.length > p && p > 0) {
              let e = [];
              for (e = c; e.length > p; ) e.pop();
              o({ variations: e });
            }
            for (let a = 0; a < p; a++)
              u.push(
                (0, e.createElement)(
                  "div",
                  { className: "text-control-two-container" },
                  (0, e.createElement)(
                    "div",
                    { className: "text-control-wrapper" },
                    (0, e.createElement)(l.TextControl, {
                      className: "variations",
                      label: (0, t.__)(
                        "Variation " + (a + 1),
                        "wp-ultra-simple-paypal-shopping-cart"
                      ),
                      value: c && c[a] ? c[a][0] : "",
                      onChange: (e) => {
                        let t = c.map((t, r) =>
                          r === a ? [n(e), ...t.slice(1)] : t
                        );
                        o({ variations: t });
                      },
                    })
                  ),
                  (0, e.createElement)(
                    "div",
                    { className: "text-control-wrapper" },
                    (0, e.createElement)(l.TextControl, {
                      className: "variations",
                      label: (0, t.__)(
                        "Price " + (a + 1),
                        "wp-ultra-simple-paypal-shopping-cart"
                      ),
                      value: c && c[a] ? c[a][1] : "",
                      onChange: (e) => {
                        let t = c.map((t, r) => (r === a ? [t[0], n(e)] : t));
                        o({ variations: t });
                      },
                    })
                  )
                )
              );
            if (!h) {
              let e = [];
              if (m > 0) for (let a = 0; a < m; a++) e.push(["", ""]);
              o({ shippings: e });
            }
            if (h && h.length < m && m > 0) {
              let e = [];
              for (Array.isArray(h) && (e = h); e.length < m; )
                e.push(["", ""]);
              o({ shippings: e });
            }
            if (h && h.length > m && m > 0) {
              let e = [];
              for (e = h; e.length > m; ) e.pop();
              o({ shippings: e });
            }
            for (let a = 0; a < m; a++)
              v.push(
                (0, e.createElement)(
                  "div",
                  { className: "text-control-two-container" },
                  (0, e.createElement)(
                    "div",
                    { className: "text-control-wrapper" },
                    (0, e.createElement)(l.TextControl, {
                      className: "shippings",
                      label: (0, t.__)(
                        "Shippings " + (a + 1),
                        "wp-ultra-simple-paypal-shopping-cart"
                      ),
                      value: h && h[a] ? h[a][0] : "",
                      onChange: (e) => {
                        let t = h.map((t, r) =>
                          r === a ? [n(e), ...t.slice(1)] : t
                        );
                        o({ shippings: t });
                      },
                    })
                  ),
                  (0, e.createElement)(
                    "div",
                    { className: "text-control-wrapper" },
                    (0, e.createElement)(l.TextControl, {
                      className: "shippings",
                      label: (0, t.__)(
                        "Price " + (a + 1),
                        "wp-ultra-simple-paypal-shopping-cart"
                      ),
                      value: h && h[a] ? h[a][1] : "",
                      onChange: (e) => {
                        let t = h.map((t, r) => (r === a ? [t[0], n(e)] : t));
                        o({ shippings: t });
                      },
                    })
                  )
                )
              );
            return (0, e.createElement)(
              e.Fragment,
              null,
              (0, e.createElement)(
                "div",
                { ...(0, r.useBlockProps)() },
                (0, e.createElement)(
                  "div",
                  {
                    id: "productInfo",
                    style: { display: "flex", flexDirection: "column" },
                  },
                  (0, e.createElement)(
                    "div",
                    { className: "product-details" },
                    (0, e.createElement)(
                      "div",
                      { className: "text-control-wrapper" },
                      (0, e.createElement)(l.TextControl, {
                        className: "productTag",
                        label: (0, t.__)(
                          "Product name",
                          "wp-ultra-simple-paypal-shopping-cart"
                        ),
                        value: i || "",
                        onChange: (e) => o({ productName: n(e) }),
                      })
                    )
                  ),
                  (0, e.createElement)(
                    "div",
                    { className: "product-details" },
                    (0, e.createElement)(
                      "div",
                      { className: "text-control-wrapper" },
                      (0, e.createElement)(l.TextControl, {
                        className: "variationTag",
                        label: (0, t.__)(
                          "Name of variation",
                          "wp-ultra-simple-paypal-shopping-cart"
                        ),
                        value: s || "",
                        onChange: (e) => {
                          const a = { variationName: n(e) };
                          o(a);
                        },
                      })
                    ),
                    (0, e.createElement)(
                      "div",
                      { className: "text-control-wrapper" },
                      (0, e.createElement)(l.TextControl, {
                        className: "variationTag",
                        label: (0, t.__)(
                          "Number of variations",
                          "wp-ultra-simple-paypal-shopping-cart"
                        ),
                        type: "number",
                        value: p || "",
                        onChange: (e) => {
                          const a = { variationNumber: n(e) };
                          o(a);
                        },
                      })
                    )
                  ),
                  u,
                  (0, e.createElement)(
                    "div",
                    { className: "text-control-wrapper" },
                    (0, e.createElement)(l.TextControl, {
                      label: (0, t.__)(
                        "Number of shipping",
                        "wp-ultra-simple-paypal-shopping-cart"
                      ),
                      type: "number",
                      value: m || "",
                      onChange: (e) => {
                        const a = { shippingNumber: n(e) };
                        o(a);
                      },
                    })
                  ),
                  v
                )
              )
            );
          },
        });
      },
    },
    t = {};
  function r(e) {
    var l = t[e];
    if (void 0 !== l) return l.exports;
    var n = (t[e] = { exports: {} });
    return a[e](n, n.exports, r), n.exports;
  }
  (r.m = a),
    (e = []),
    (r.O = (a, t, l, n) => {
      if (!t) {
        var o = 1 / 0;
        for (c = 0; c < e.length; c++) {
          for (var [t, l, n] = e[c], i = !0, p = 0; p < t.length; p++)
            (!1 & n || o >= n) && Object.keys(r.O).every((e) => r.O[e](t[p]))
              ? t.splice(p--, 1)
              : ((i = !1), n < o && (o = n));
          if (i) {
            e.splice(c--, 1);
            var s = l();
            void 0 !== s && (a = s);
          }
        }
        return a;
      }
      n = n || 0;
      for (var c = e.length; c > 0 && e[c - 1][2] > n; c--) e[c] = e[c - 1];
      e[c] = [t, l, n];
    }),
    (r.o = (e, a) => Object.prototype.hasOwnProperty.call(e, a)),
    (() => {
      var e = { 57: 0, 350: 0 };
      r.O.j = (a) => 0 === e[a];
      var a = (a, t) => {
          var l,
            n,
            [o, i, p] = t,
            s = 0;
          if (o.some((a) => 0 !== e[a])) {
            for (l in i) r.o(i, l) && (r.m[l] = i[l]);
            if (p) var c = p(r);
          }
          for (a && a(t); s < o.length; s++)
            (n = o[s]), r.o(e, n) && e[n] && e[n][0](), (e[n] = 0);
          return r.O(c);
        },
        t = (globalThis.webpackChunkcopyright_date_block =
          globalThis.webpackChunkcopyright_date_block || []);
      t.forEach(a.bind(null, 0)), (t.push = a.bind(null, t.push.bind(t)));
    })();
  var l = r.O(void 0, [350], () => r(794));
  l = r.O(l);
})();
