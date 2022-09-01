/******/ (function() { // webpackBootstrap
/******/ 	"use strict";
/******/ 	var __webpack_modules__ = ({

/***/ "./assets/js/admin/core.js":
/*!*********************************!*\
  !*** ./assets/js/admin/core.js ***!
  \*********************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": function() { return /* binding */ core; }
/* harmony export */ });
/* harmony import */ var jquery__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! jquery */ "jquery");
/* harmony import */ var jquery__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(jquery__WEBPACK_IMPORTED_MODULE_0__);
/*! jQuery Timepicker Addon - v1.6.3 - 2016-04-20
 * http://trentrichardson.com/examples/timepicker
 * Copyright (c) 2016 Trent Richardson; Licensed MIT */
!function (a) {
  "function" == typeof define && __webpack_require__.amdO ? define(["jquery", "jquery-ui"], a) : a((jquery__WEBPACK_IMPORTED_MODULE_0___default()));
}(function ($) {
  if ($.ui.timepicker = $.ui.timepicker || {}, !$.ui.timepicker.version) {
    $.extend($.ui, {
      timepicker: {
        version: "1.6.3"
      }
    });

    var Timepicker = function () {
      this.regional = [], this.regional[""] = {
        currentText: "Now",
        closeText: "Done",
        amNames: ["AM", "A"],
        pmNames: ["PM", "P"],
        timeFormat: "HH:mm",
        timeSuffix: "",
        timeOnlyTitle: "Choose Time",
        timeText: "Time",
        hourText: "Hour",
        minuteText: "Minute",
        secondText: "Second",
        millisecText: "Millisecond",
        microsecText: "Microsecond",
        timezoneText: "Time Zone",
        isRTL: !1
      }, this._defaults = {
        showButtonPanel: !0,
        timeOnly: !1,
        timeOnlyShowDate: !1,
        showHour: null,
        showMinute: null,
        showSecond: null,
        showMillisec: null,
        showMicrosec: null,
        showTimezone: null,
        showTime: !0,
        stepHour: 1,
        stepMinute: 1,
        stepSecond: 1,
        stepMillisec: 1,
        stepMicrosec: 1,
        hour: 0,
        minute: 0,
        second: 0,
        millisec: 0,
        microsec: 0,
        timezone: null,
        hourMin: 0,
        minuteMin: 0,
        secondMin: 0,
        millisecMin: 0,
        microsecMin: 0,
        hourMax: 23,
        minuteMax: 59,
        secondMax: 59,
        millisecMax: 999,
        microsecMax: 999,
        minDateTime: null,
        maxDateTime: null,
        maxTime: null,
        minTime: null,
        onSelect: null,
        hourGrid: 0,
        minuteGrid: 0,
        secondGrid: 0,
        millisecGrid: 0,
        microsecGrid: 0,
        alwaysSetTime: !0,
        separator: " ",
        altFieldTimeOnly: !0,
        altTimeFormat: null,
        altSeparator: null,
        altTimeSuffix: null,
        altRedirectFocus: !0,
        pickerTimeFormat: null,
        pickerTimeSuffix: null,
        showTimepicker: !0,
        timezoneList: null,
        addSliderAccess: !1,
        sliderAccessArgs: null,
        controlType: "slider",
        oneLine: !1,
        defaultValue: null,
        parse: "strict",
        afterInject: null
      }, $.extend(this._defaults, this.regional[""]);
    };

    $.extend(Timepicker.prototype, {
      $input: null,
      $altInput: null,
      $timeObj: null,
      inst: null,
      hour_slider: null,
      minute_slider: null,
      second_slider: null,
      millisec_slider: null,
      microsec_slider: null,
      timezone_select: null,
      maxTime: null,
      minTime: null,
      hour: 0,
      minute: 0,
      second: 0,
      millisec: 0,
      microsec: 0,
      timezone: null,
      hourMinOriginal: null,
      minuteMinOriginal: null,
      secondMinOriginal: null,
      millisecMinOriginal: null,
      microsecMinOriginal: null,
      hourMaxOriginal: null,
      minuteMaxOriginal: null,
      secondMaxOriginal: null,
      millisecMaxOriginal: null,
      microsecMaxOriginal: null,
      ampm: "",
      formattedDate: "",
      formattedTime: "",
      formattedDateTime: "",
      timezoneList: null,
      units: ["hour", "minute", "second", "millisec", "microsec"],
      support: {},
      control: null,
      setDefaults: function (a) {
        return extendRemove(this._defaults, a || {}), this;
      },
      _newInst: function ($input, opts) {
        var tp_inst = new Timepicker(),
            inlineSettings = {},
            fns = {},
            overrides,
            i;

        for (var attrName in this._defaults) if (this._defaults.hasOwnProperty(attrName)) {
          var attrValue = $input.attr("time:" + attrName);
          if (attrValue) try {
            inlineSettings[attrName] = eval(attrValue);
          } catch (err) {
            inlineSettings[attrName] = attrValue;
          }
        }

        overrides = {
          beforeShow: function (a, b) {
            return $.isFunction(tp_inst._defaults.evnts.beforeShow) ? tp_inst._defaults.evnts.beforeShow.call($input[0], a, b, tp_inst) : void 0;
          },
          onChangeMonthYear: function (a, b, c) {
            $.isFunction(tp_inst._defaults.evnts.onChangeMonthYear) && tp_inst._defaults.evnts.onChangeMonthYear.call($input[0], a, b, c, tp_inst);
          },
          onClose: function (a, b) {
            tp_inst.timeDefined === !0 && "" !== $input.val() && tp_inst._updateDateTime(b), $.isFunction(tp_inst._defaults.evnts.onClose) && tp_inst._defaults.evnts.onClose.call($input[0], a, b, tp_inst);
          }
        };

        for (i in overrides) overrides.hasOwnProperty(i) && (fns[i] = opts[i] || this._defaults[i] || null);

        tp_inst._defaults = $.extend({}, this._defaults, inlineSettings, opts, overrides, {
          evnts: fns,
          timepicker: tp_inst
        }), tp_inst.amNames = $.map(tp_inst._defaults.amNames, function (a) {
          return a.toUpperCase();
        }), tp_inst.pmNames = $.map(tp_inst._defaults.pmNames, function (a) {
          return a.toUpperCase();
        }), tp_inst.support = detectSupport(tp_inst._defaults.timeFormat + (tp_inst._defaults.pickerTimeFormat ? tp_inst._defaults.pickerTimeFormat : "") + (tp_inst._defaults.altTimeFormat ? tp_inst._defaults.altTimeFormat : "")), "string" == typeof tp_inst._defaults.controlType ? ("slider" === tp_inst._defaults.controlType && "undefined" == typeof $.ui.slider && (tp_inst._defaults.controlType = "select"), tp_inst.control = tp_inst._controls[tp_inst._defaults.controlType]) : tp_inst.control = tp_inst._defaults.controlType;
        var timezoneList = [-720, -660, -600, -570, -540, -480, -420, -360, -300, -270, -240, -210, -180, -120, -60, 0, 60, 120, 180, 210, 240, 270, 300, 330, 345, 360, 390, 420, 480, 525, 540, 570, 600, 630, 660, 690, 720, 765, 780, 840];
        null !== tp_inst._defaults.timezoneList && (timezoneList = tp_inst._defaults.timezoneList);
        var tzl = timezoneList.length,
            tzi = 0,
            tzv = null;
        if (tzl > 0 && "object" != typeof timezoneList[0]) for (; tzl > tzi; tzi++) tzv = timezoneList[tzi], timezoneList[tzi] = {
          value: tzv,
          label: $.timepicker.timezoneOffsetString(tzv, tp_inst.support.iso8601)
        };
        return tp_inst._defaults.timezoneList = timezoneList, tp_inst.timezone = null !== tp_inst._defaults.timezone ? $.timepicker.timezoneOffsetNumber(tp_inst._defaults.timezone) : -1 * new Date().getTimezoneOffset(), tp_inst.hour = tp_inst._defaults.hour < tp_inst._defaults.hourMin ? tp_inst._defaults.hourMin : tp_inst._defaults.hour > tp_inst._defaults.hourMax ? tp_inst._defaults.hourMax : tp_inst._defaults.hour, tp_inst.minute = tp_inst._defaults.minute < tp_inst._defaults.minuteMin ? tp_inst._defaults.minuteMin : tp_inst._defaults.minute > tp_inst._defaults.minuteMax ? tp_inst._defaults.minuteMax : tp_inst._defaults.minute, tp_inst.second = tp_inst._defaults.second < tp_inst._defaults.secondMin ? tp_inst._defaults.secondMin : tp_inst._defaults.second > tp_inst._defaults.secondMax ? tp_inst._defaults.secondMax : tp_inst._defaults.second, tp_inst.millisec = tp_inst._defaults.millisec < tp_inst._defaults.millisecMin ? tp_inst._defaults.millisecMin : tp_inst._defaults.millisec > tp_inst._defaults.millisecMax ? tp_inst._defaults.millisecMax : tp_inst._defaults.millisec, tp_inst.microsec = tp_inst._defaults.microsec < tp_inst._defaults.microsecMin ? tp_inst._defaults.microsecMin : tp_inst._defaults.microsec > tp_inst._defaults.microsecMax ? tp_inst._defaults.microsecMax : tp_inst._defaults.microsec, tp_inst.ampm = "", tp_inst.$input = $input, tp_inst._defaults.altField && (tp_inst.$altInput = $(tp_inst._defaults.altField), tp_inst._defaults.altRedirectFocus === !0 && tp_inst.$altInput.css({
          cursor: "pointer"
        }).focus(function () {
          $input.trigger("focus");
        })), (0 === tp_inst._defaults.minDate || 0 === tp_inst._defaults.minDateTime) && (tp_inst._defaults.minDate = new Date()), (0 === tp_inst._defaults.maxDate || 0 === tp_inst._defaults.maxDateTime) && (tp_inst._defaults.maxDate = new Date()), void 0 !== tp_inst._defaults.minDate && tp_inst._defaults.minDate instanceof Date && (tp_inst._defaults.minDateTime = new Date(tp_inst._defaults.minDate.getTime())), void 0 !== tp_inst._defaults.minDateTime && tp_inst._defaults.minDateTime instanceof Date && (tp_inst._defaults.minDate = new Date(tp_inst._defaults.minDateTime.getTime())), void 0 !== tp_inst._defaults.maxDate && tp_inst._defaults.maxDate instanceof Date && (tp_inst._defaults.maxDateTime = new Date(tp_inst._defaults.maxDate.getTime())), void 0 !== tp_inst._defaults.maxDateTime && tp_inst._defaults.maxDateTime instanceof Date && (tp_inst._defaults.maxDate = new Date(tp_inst._defaults.maxDateTime.getTime())), tp_inst.$input.bind("focus", function () {
          tp_inst._onFocus();
        }), tp_inst;
      },
      _addTimePicker: function (a) {
        var b = $.trim(this.$altInput && this._defaults.altFieldTimeOnly ? this.$input.val() + " " + this.$altInput.val() : this.$input.val());
        this.timeDefined = this._parseTime(b), this._limitMinMaxDateTime(a, !1), this._injectTimePicker(), this._afterInject();
      },
      _parseTime: function (a, b) {
        if (this.inst || (this.inst = $.datepicker._getInst(this.$input[0])), b || !this._defaults.timeOnly) {
          var c = $.datepicker._get(this.inst, "dateFormat");

          try {
            var d = parseDateTimeInternal(c, this._defaults.timeFormat, a, $.datepicker._getFormatConfig(this.inst), this._defaults);
            if (!d.timeObj) return !1;
            $.extend(this, d.timeObj);
          } catch (e) {
            return $.timepicker.log("Error parsing the date/time string: " + e + "\ndate/time string = " + a + "\ntimeFormat = " + this._defaults.timeFormat + "\ndateFormat = " + c), !1;
          }

          return !0;
        }

        var f = $.datepicker.parseTime(this._defaults.timeFormat, a, this._defaults);
        return f ? ($.extend(this, f), !0) : !1;
      },
      _afterInject: function () {
        var a = this.inst.settings;
        $.isFunction(a.afterInject) && a.afterInject.call(this);
      },
      _injectTimePicker: function () {
        var a = this.inst.dpDiv,
            b = this.inst.settings,
            c = this,
            d = "",
            e = "",
            f = null,
            g = {},
            h = {},
            i = null,
            j = 0,
            k = 0;

        if (0 === a.find("div.ui-timepicker-div").length && b.showTimepicker) {
          var l = " ui_tpicker_unit_hide",
              m = '<div class="ui-timepicker-div' + (b.isRTL ? " ui-timepicker-rtl" : "") + (b.oneLine && "select" === b.controlType ? " ui-timepicker-oneLine" : "") + '"><dl><dt class="ui_tpicker_time_label' + (b.showTime ? "" : l) + '">' + b.timeText + '</dt><dd class="ui_tpicker_time ' + (b.showTime ? "" : l) + '"><input class="ui_tpicker_time_input" ' + (b.timeInput ? "" : "disabled") + "/></dd>";

          for (j = 0, k = this.units.length; k > j; j++) {
            if (d = this.units[j], e = d.substr(0, 1).toUpperCase() + d.substr(1), f = null !== b["show" + e] ? b["show" + e] : this.support[d], g[d] = parseInt(b[d + "Max"] - (b[d + "Max"] - b[d + "Min"]) % b["step" + e], 10), h[d] = 0, m += '<dt class="ui_tpicker_' + d + "_label" + (f ? "" : l) + '">' + b[d + "Text"] + '</dt><dd class="ui_tpicker_' + d + (f ? "" : l) + '"><div class="ui_tpicker_' + d + "_slider" + (f ? "" : l) + '"></div>', f && b[d + "Grid"] > 0) {
              if (m += '<div style="padding-left: 1px"><table class="ui-tpicker-grid-label"><tr>', "hour" === d) for (var n = b[d + "Min"]; n <= g[d]; n += parseInt(b[d + "Grid"], 10)) {
                h[d]++;
                var o = $.datepicker.formatTime(this.support.ampm ? "hht" : "HH", {
                  hour: n
                }, b);
                m += '<td data-for="' + d + '">' + o + "</td>";
              } else for (var p = b[d + "Min"]; p <= g[d]; p += parseInt(b[d + "Grid"], 10)) h[d]++, m += '<td data-for="' + d + '">' + (10 > p ? "0" : "") + p + "</td>";
              m += "</tr></table></div>";
            }

            m += "</dd>";
          }

          var q = null !== b.showTimezone ? b.showTimezone : this.support.timezone;
          m += '<dt class="ui_tpicker_timezone_label' + (q ? "" : l) + '">' + b.timezoneText + "</dt>", m += '<dd class="ui_tpicker_timezone' + (q ? "" : l) + '"></dd>', m += "</dl></div>";
          var r = $(m);

          for (b.timeOnly === !0 && (r.prepend('<div class="ui-widget-header ui-helper-clearfix ui-corner-all"><div class="ui-datepicker-title">' + b.timeOnlyTitle + "</div></div>"), a.find(".ui-datepicker-header, .ui-datepicker-calendar").hide()), j = 0, k = c.units.length; k > j; j++) d = c.units[j], e = d.substr(0, 1).toUpperCase() + d.substr(1), f = null !== b["show" + e] ? b["show" + e] : this.support[d], c[d + "_slider"] = c.control.create(c, r.find(".ui_tpicker_" + d + "_slider"), d, c[d], b[d + "Min"], g[d], b["step" + e]), f && b[d + "Grid"] > 0 && (i = 100 * h[d] * b[d + "Grid"] / (g[d] - b[d + "Min"]), r.find(".ui_tpicker_" + d + " table").css({
            width: i + "%",
            marginLeft: b.isRTL ? "0" : i / (-2 * h[d]) + "%",
            marginRight: b.isRTL ? i / (-2 * h[d]) + "%" : "0",
            borderCollapse: "collapse"
          }).find("td").click(function (a) {
            var b = $(this),
                e = b.html(),
                f = parseInt(e.replace(/[^0-9]/g), 10),
                g = e.replace(/[^apm]/gi),
                h = b.data("for");
            "hour" === h && (-1 !== g.indexOf("p") && 12 > f ? f += 12 : -1 !== g.indexOf("a") && 12 === f && (f = 0)), c.control.value(c, c[h + "_slider"], d, f), c._onTimeChange(), c._onSelectHandler();
          }).css({
            cursor: "pointer",
            width: 100 / h[d] + "%",
            textAlign: "center",
            overflow: "hidden"
          }));

          if (this.timezone_select = r.find(".ui_tpicker_timezone").append("<select></select>").find("select"), $.fn.append.apply(this.timezone_select, $.map(b.timezoneList, function (a, b) {
            return $("<option />").val("object" == typeof a ? a.value : a).text("object" == typeof a ? a.label : a);
          })), "undefined" != typeof this.timezone && null !== this.timezone && "" !== this.timezone) {
            var s = -1 * new Date(this.inst.selectedYear, this.inst.selectedMonth, this.inst.selectedDay, 12).getTimezoneOffset();
            s === this.timezone ? selectLocalTimezone(c) : this.timezone_select.val(this.timezone);
          } else "undefined" != typeof this.hour && null !== this.hour && "" !== this.hour ? this.timezone_select.val(b.timezone) : selectLocalTimezone(c);

          this.timezone_select.change(function () {
            c._onTimeChange(), c._onSelectHandler(), c._afterInject();
          });
          var t = a.find(".ui-datepicker-buttonpane");

          if (t.length ? t.before(r) : a.append(r), this.$timeObj = r.find(".ui_tpicker_time_input"), this.$timeObj.change(function () {
            var a = c.inst.settings.timeFormat,
                b = $.datepicker.parseTime(a, this.value),
                d = new Date();
            b ? (d.setHours(b.hour), d.setMinutes(b.minute), d.setSeconds(b.second), $.datepicker._setTime(c.inst, d)) : (this.value = c.formattedTime, this.blur());
          }), null !== this.inst) {
            var u = this.timeDefined;
            this._onTimeChange(), this.timeDefined = u;
          }

          if (this._defaults.addSliderAccess) {
            var v = this._defaults.sliderAccessArgs,
                w = this._defaults.isRTL;
            v.isRTL = w, setTimeout(function () {
              if (0 === r.find(".ui-slider-access").length) {
                r.find(".ui-slider:visible").sliderAccess(v);
                var a = r.find(".ui-slider-access:eq(0)").outerWidth(!0);
                a && r.find("table:visible").each(function () {
                  var b = $(this),
                      c = b.outerWidth(),
                      d = b.css(w ? "marginRight" : "marginLeft").toString().replace("%", ""),
                      e = c - a,
                      f = d * e / c + "%",
                      g = {
                    width: e,
                    marginRight: 0,
                    marginLeft: 0
                  };
                  g[w ? "marginRight" : "marginLeft"] = f, b.css(g);
                });
              }
            }, 10);
          }

          c._limitMinMaxDateTime(this.inst, !0);
        }
      },
      _limitMinMaxDateTime: function (a, b) {
        var c = this._defaults,
            d = new Date(a.selectedYear, a.selectedMonth, a.selectedDay);

        if (this._defaults.showTimepicker) {
          if (null !== $.datepicker._get(a, "minDateTime") && void 0 !== $.datepicker._get(a, "minDateTime") && d) {
            var e = $.datepicker._get(a, "minDateTime"),
                f = new Date(e.getFullYear(), e.getMonth(), e.getDate(), 0, 0, 0, 0);

            (null === this.hourMinOriginal || null === this.minuteMinOriginal || null === this.secondMinOriginal || null === this.millisecMinOriginal || null === this.microsecMinOriginal) && (this.hourMinOriginal = c.hourMin, this.minuteMinOriginal = c.minuteMin, this.secondMinOriginal = c.secondMin, this.millisecMinOriginal = c.millisecMin, this.microsecMinOriginal = c.microsecMin), a.settings.timeOnly || f.getTime() === d.getTime() ? (this._defaults.hourMin = e.getHours(), this.hour <= this._defaults.hourMin ? (this.hour = this._defaults.hourMin, this._defaults.minuteMin = e.getMinutes(), this.minute <= this._defaults.minuteMin ? (this.minute = this._defaults.minuteMin, this._defaults.secondMin = e.getSeconds(), this.second <= this._defaults.secondMin ? (this.second = this._defaults.secondMin, this._defaults.millisecMin = e.getMilliseconds(), this.millisec <= this._defaults.millisecMin ? (this.millisec = this._defaults.millisecMin, this._defaults.microsecMin = e.getMicroseconds()) : (this.microsec < this._defaults.microsecMin && (this.microsec = this._defaults.microsecMin), this._defaults.microsecMin = this.microsecMinOriginal)) : (this._defaults.millisecMin = this.millisecMinOriginal, this._defaults.microsecMin = this.microsecMinOriginal)) : (this._defaults.secondMin = this.secondMinOriginal, this._defaults.millisecMin = this.millisecMinOriginal, this._defaults.microsecMin = this.microsecMinOriginal)) : (this._defaults.minuteMin = this.minuteMinOriginal, this._defaults.secondMin = this.secondMinOriginal, this._defaults.millisecMin = this.millisecMinOriginal, this._defaults.microsecMin = this.microsecMinOriginal)) : (this._defaults.hourMin = this.hourMinOriginal, this._defaults.minuteMin = this.minuteMinOriginal, this._defaults.secondMin = this.secondMinOriginal, this._defaults.millisecMin = this.millisecMinOriginal, this._defaults.microsecMin = this.microsecMinOriginal);
          }

          if (null !== $.datepicker._get(a, "maxDateTime") && void 0 !== $.datepicker._get(a, "maxDateTime") && d) {
            var g = $.datepicker._get(a, "maxDateTime"),
                h = new Date(g.getFullYear(), g.getMonth(), g.getDate(), 0, 0, 0, 0);

            (null === this.hourMaxOriginal || null === this.minuteMaxOriginal || null === this.secondMaxOriginal || null === this.millisecMaxOriginal) && (this.hourMaxOriginal = c.hourMax, this.minuteMaxOriginal = c.minuteMax, this.secondMaxOriginal = c.secondMax, this.millisecMaxOriginal = c.millisecMax, this.microsecMaxOriginal = c.microsecMax), a.settings.timeOnly || h.getTime() === d.getTime() ? (this._defaults.hourMax = g.getHours(), this.hour >= this._defaults.hourMax ? (this.hour = this._defaults.hourMax, this._defaults.minuteMax = g.getMinutes(), this.minute >= this._defaults.minuteMax ? (this.minute = this._defaults.minuteMax, this._defaults.secondMax = g.getSeconds(), this.second >= this._defaults.secondMax ? (this.second = this._defaults.secondMax, this._defaults.millisecMax = g.getMilliseconds(), this.millisec >= this._defaults.millisecMax ? (this.millisec = this._defaults.millisecMax, this._defaults.microsecMax = g.getMicroseconds()) : (this.microsec > this._defaults.microsecMax && (this.microsec = this._defaults.microsecMax), this._defaults.microsecMax = this.microsecMaxOriginal)) : (this._defaults.millisecMax = this.millisecMaxOriginal, this._defaults.microsecMax = this.microsecMaxOriginal)) : (this._defaults.secondMax = this.secondMaxOriginal, this._defaults.millisecMax = this.millisecMaxOriginal, this._defaults.microsecMax = this.microsecMaxOriginal)) : (this._defaults.minuteMax = this.minuteMaxOriginal, this._defaults.secondMax = this.secondMaxOriginal, this._defaults.millisecMax = this.millisecMaxOriginal, this._defaults.microsecMax = this.microsecMaxOriginal)) : (this._defaults.hourMax = this.hourMaxOriginal, this._defaults.minuteMax = this.minuteMaxOriginal, this._defaults.secondMax = this.secondMaxOriginal, this._defaults.millisecMax = this.millisecMaxOriginal, this._defaults.microsecMax = this.microsecMaxOriginal);
          }

          if (null !== a.settings.minTime) {
            var i = new Date("01/01/1970 " + a.settings.minTime);
            this.hour < i.getHours() ? (this.hour = this._defaults.hourMin = i.getHours(), this.minute = this._defaults.minuteMin = i.getMinutes()) : this.hour === i.getHours() && this.minute < i.getMinutes() ? this.minute = this._defaults.minuteMin = i.getMinutes() : this._defaults.hourMin < i.getHours() ? (this._defaults.hourMin = i.getHours(), this._defaults.minuteMin = i.getMinutes()) : this._defaults.hourMin === i.getHours() === this.hour && this._defaults.minuteMin < i.getMinutes() ? this._defaults.minuteMin = i.getMinutes() : this._defaults.minuteMin = 0;
          }

          if (null !== a.settings.maxTime) {
            var j = new Date("01/01/1970 " + a.settings.maxTime);
            this.hour > j.getHours() ? (this.hour = this._defaults.hourMax = j.getHours(), this.minute = this._defaults.minuteMax = j.getMinutes()) : this.hour === j.getHours() && this.minute > j.getMinutes() ? this.minute = this._defaults.minuteMax = j.getMinutes() : this._defaults.hourMax > j.getHours() ? (this._defaults.hourMax = j.getHours(), this._defaults.minuteMax = j.getMinutes()) : this._defaults.hourMax === j.getHours() === this.hour && this._defaults.minuteMax > j.getMinutes() ? this._defaults.minuteMax = j.getMinutes() : this._defaults.minuteMax = 59;
          }

          if (void 0 !== b && b === !0) {
            var k = parseInt(this._defaults.hourMax - (this._defaults.hourMax - this._defaults.hourMin) % this._defaults.stepHour, 10),
                l = parseInt(this._defaults.minuteMax - (this._defaults.minuteMax - this._defaults.minuteMin) % this._defaults.stepMinute, 10),
                m = parseInt(this._defaults.secondMax - (this._defaults.secondMax - this._defaults.secondMin) % this._defaults.stepSecond, 10),
                n = parseInt(this._defaults.millisecMax - (this._defaults.millisecMax - this._defaults.millisecMin) % this._defaults.stepMillisec, 10),
                o = parseInt(this._defaults.microsecMax - (this._defaults.microsecMax - this._defaults.microsecMin) % this._defaults.stepMicrosec, 10);
            this.hour_slider && (this.control.options(this, this.hour_slider, "hour", {
              min: this._defaults.hourMin,
              max: k,
              step: this._defaults.stepHour
            }), this.control.value(this, this.hour_slider, "hour", this.hour - this.hour % this._defaults.stepHour)), this.minute_slider && (this.control.options(this, this.minute_slider, "minute", {
              min: this._defaults.minuteMin,
              max: l,
              step: this._defaults.stepMinute
            }), this.control.value(this, this.minute_slider, "minute", this.minute - this.minute % this._defaults.stepMinute)), this.second_slider && (this.control.options(this, this.second_slider, "second", {
              min: this._defaults.secondMin,
              max: m,
              step: this._defaults.stepSecond
            }), this.control.value(this, this.second_slider, "second", this.second - this.second % this._defaults.stepSecond)), this.millisec_slider && (this.control.options(this, this.millisec_slider, "millisec", {
              min: this._defaults.millisecMin,
              max: n,
              step: this._defaults.stepMillisec
            }), this.control.value(this, this.millisec_slider, "millisec", this.millisec - this.millisec % this._defaults.stepMillisec)), this.microsec_slider && (this.control.options(this, this.microsec_slider, "microsec", {
              min: this._defaults.microsecMin,
              max: o,
              step: this._defaults.stepMicrosec
            }), this.control.value(this, this.microsec_slider, "microsec", this.microsec - this.microsec % this._defaults.stepMicrosec));
          }
        }
      },
      _onTimeChange: function () {
        if (this._defaults.showTimepicker) {
          var a = this.hour_slider ? this.control.value(this, this.hour_slider, "hour") : !1,
              b = this.minute_slider ? this.control.value(this, this.minute_slider, "minute") : !1,
              c = this.second_slider ? this.control.value(this, this.second_slider, "second") : !1,
              d = this.millisec_slider ? this.control.value(this, this.millisec_slider, "millisec") : !1,
              e = this.microsec_slider ? this.control.value(this, this.microsec_slider, "microsec") : !1,
              f = this.timezone_select ? this.timezone_select.val() : !1,
              g = this._defaults,
              h = g.pickerTimeFormat || g.timeFormat,
              i = g.pickerTimeSuffix || g.timeSuffix;
          "object" == typeof a && (a = !1), "object" == typeof b && (b = !1), "object" == typeof c && (c = !1), "object" == typeof d && (d = !1), "object" == typeof e && (e = !1), "object" == typeof f && (f = !1), a !== !1 && (a = parseInt(a, 10)), b !== !1 && (b = parseInt(b, 10)), c !== !1 && (c = parseInt(c, 10)), d !== !1 && (d = parseInt(d, 10)), e !== !1 && (e = parseInt(e, 10)), f !== !1 && (f = f.toString());
          var j = g[12 > a ? "amNames" : "pmNames"][0],
              k = a !== parseInt(this.hour, 10) || b !== parseInt(this.minute, 10) || c !== parseInt(this.second, 10) || d !== parseInt(this.millisec, 10) || e !== parseInt(this.microsec, 10) || this.ampm.length > 0 && 12 > a != (-1 !== $.inArray(this.ampm.toUpperCase(), this.amNames)) || null !== this.timezone && f !== this.timezone.toString();

          if (k && (a !== !1 && (this.hour = a), b !== !1 && (this.minute = b), c !== !1 && (this.second = c), d !== !1 && (this.millisec = d), e !== !1 && (this.microsec = e), f !== !1 && (this.timezone = f), this.inst || (this.inst = $.datepicker._getInst(this.$input[0])), this._limitMinMaxDateTime(this.inst, !0)), this.support.ampm && (this.ampm = j), this.formattedTime = $.datepicker.formatTime(g.timeFormat, this, g), this.$timeObj && (this.$timeObj.val(h === g.timeFormat ? this.formattedTime + i : $.datepicker.formatTime(h, this, g) + i), this.$timeObj[0].setSelectionRange)) {
            var l = this.$timeObj[0].selectionStart,
                m = this.$timeObj[0].selectionEnd;
            this.$timeObj[0].setSelectionRange(l, m);
          }

          this.timeDefined = !0, k && this._updateDateTime();
        }
      },
      _onSelectHandler: function () {
        var a = this._defaults.onSelect || this.inst.settings.onSelect,
            b = this.$input ? this.$input[0] : null;
        a && b && a.apply(b, [this.formattedDateTime, this]);
      },
      _updateDateTime: function (a) {
        a = this.inst || a;

        var b = a.currentYear > 0 ? new Date(a.currentYear, a.currentMonth, a.currentDay) : new Date(a.selectedYear, a.selectedMonth, a.selectedDay),
            c = $.datepicker._daylightSavingAdjust(b),
            d = $.datepicker._get(a, "dateFormat"),
            e = $.datepicker._getFormatConfig(a),
            f = null !== c && this.timeDefined;

        this.formattedDate = $.datepicker.formatDate(d, null === c ? new Date() : c, e);
        var g = this.formattedDate;
        if ("" === a.lastVal && (a.currentYear = a.selectedYear, a.currentMonth = a.selectedMonth, a.currentDay = a.selectedDay), this._defaults.timeOnly === !0 && this._defaults.timeOnlyShowDate === !1 ? g = this.formattedTime : (this._defaults.timeOnly !== !0 && (this._defaults.alwaysSetTime || f) || this._defaults.timeOnly === !0 && this._defaults.timeOnlyShowDate === !0) && (g += this._defaults.separator + this.formattedTime + this._defaults.timeSuffix), this.formattedDateTime = g, this._defaults.showTimepicker) {
          if (this.$altInput && this._defaults.timeOnly === !1 && this._defaults.altFieldTimeOnly === !0) this.$altInput.val(this.formattedTime), this.$input.val(this.formattedDate);else if (this.$altInput) {
            this.$input.val(g);
            var h = "",
                i = null !== this._defaults.altSeparator ? this._defaults.altSeparator : this._defaults.separator,
                j = null !== this._defaults.altTimeSuffix ? this._defaults.altTimeSuffix : this._defaults.timeSuffix;
            this._defaults.timeOnly || (h = this._defaults.altFormat ? $.datepicker.formatDate(this._defaults.altFormat, null === c ? new Date() : c, e) : this.formattedDate, h && (h += i)), h += null !== this._defaults.altTimeFormat ? $.datepicker.formatTime(this._defaults.altTimeFormat, this, this._defaults) + j : this.formattedTime + j, this.$altInput.val(h);
          } else this.$input.val(g);
        } else this.$input.val(this.formattedDate);
        this.$input.trigger("change");
      },
      _onFocus: function () {
        if (!this.$input.val() && this._defaults.defaultValue) {
          this.$input.val(this._defaults.defaultValue);

          var a = $.datepicker._getInst(this.$input.get(0)),
              b = $.datepicker._get(a, "timepicker");

          if (b && b._defaults.timeOnly && a.input.val() !== a.lastVal) try {
            $.datepicker._updateDatepicker(a);
          } catch (c) {
            $.timepicker.log(c);
          }
        }
      },
      _controls: {
        slider: {
          create: function (a, b, c, d, e, f, g) {
            var h = a._defaults.isRTL;
            return b.prop("slide", null).slider({
              orientation: "horizontal",
              value: h ? -1 * d : d,
              min: h ? -1 * f : e,
              max: h ? -1 * e : f,
              step: g,
              slide: function (b, d) {
                a.control.value(a, $(this), c, h ? -1 * d.value : d.value), a._onTimeChange();
              },
              stop: function (b, c) {
                a._onSelectHandler();
              }
            });
          },
          options: function (a, b, c, d, e) {
            if (a._defaults.isRTL) {
              if ("string" == typeof d) return "min" === d || "max" === d ? void 0 !== e ? b.slider(d, -1 * e) : Math.abs(b.slider(d)) : b.slider(d);
              var f = d.min,
                  g = d.max;
              return d.min = d.max = null, void 0 !== f && (d.max = -1 * f), void 0 !== g && (d.min = -1 * g), b.slider(d);
            }

            return "string" == typeof d && void 0 !== e ? b.slider(d, e) : b.slider(d);
          },
          value: function (a, b, c, d) {
            return a._defaults.isRTL ? void 0 !== d ? b.slider("value", -1 * d) : Math.abs(b.slider("value")) : void 0 !== d ? b.slider("value", d) : b.slider("value");
          }
        },
        select: {
          create: function (a, b, c, d, e, f, g) {
            for (var h = '<select class="ui-timepicker-select ui-state-default ui-corner-all" data-unit="' + c + '" data-min="' + e + '" data-max="' + f + '" data-step="' + g + '">', i = a._defaults.pickerTimeFormat || a._defaults.timeFormat, j = e; f >= j; j += g) h += '<option value="' + j + '"' + (j === d ? " selected" : "") + ">", h += "hour" === c ? $.datepicker.formatTime($.trim(i.replace(/[^ht ]/gi, "")), {
              hour: j
            }, a._defaults) : "millisec" === c || "microsec" === c || j >= 10 ? j : "0" + j.toString(), h += "</option>";

            return h += "</select>", b.children("select").remove(), $(h).appendTo(b).change(function (b) {
              a._onTimeChange(), a._onSelectHandler(), a._afterInject();
            }), b;
          },
          options: function (a, b, c, d, e) {
            var f = {},
                g = b.children("select");

            if ("string" == typeof d) {
              if (void 0 === e) return g.data(d);
              f[d] = e;
            } else f = d;

            return a.control.create(a, b, g.data("unit"), g.val(), f.min >= 0 ? f.min : g.data("min"), f.max || g.data("max"), f.step || g.data("step"));
          },
          value: function (a, b, c, d) {
            var e = b.children("select");
            return void 0 !== d ? e.val(d) : e.val();
          }
        }
      }
    }), $.fn.extend({
      timepicker: function (a) {
        a = a || {};
        var b = Array.prototype.slice.call(arguments);
        return "object" == typeof a && (b[0] = $.extend(a, {
          timeOnly: !0
        })), $(this).each(function () {
          $.fn.datetimepicker.apply($(this), b);
        });
      },
      datetimepicker: function (a) {
        a = a || {};
        var b = arguments;
        return "string" == typeof a ? "getDate" === a || "option" === a && 2 === b.length && "string" == typeof b[1] ? $.fn.datepicker.apply($(this[0]), b) : this.each(function () {
          var a = $(this);
          a.datepicker.apply(a, b);
        }) : this.each(function () {
          var b = $(this);
          b.datepicker($.timepicker._newInst(b, a)._defaults);
        });
      }
    }), $.datepicker.parseDateTime = function (a, b, c, d, e) {
      var f = parseDateTimeInternal(a, b, c, d, e);

      if (f.timeObj) {
        var g = f.timeObj;
        f.date.setHours(g.hour, g.minute, g.second, g.millisec), f.date.setMicroseconds(g.microsec);
      }

      return f.date;
    }, $.datepicker.parseTime = function (a, b, c) {
      var d = extendRemove(extendRemove({}, $.timepicker._defaults), c || {}),
          e = (-1 !== a.replace(/\'.*?\'/g, "").indexOf("Z"), function (a, b, c) {
        var d,
            e = function (a, b) {
          var c = [];
          return a && $.merge(c, a), b && $.merge(c, b), c = $.map(c, function (a) {
            return a.replace(/[.*+?|()\[\]{}\\]/g, "\\$&");
          }), "(" + c.join("|") + ")?";
        },
            f = function (a) {
          var b = a.toLowerCase().match(/(h{1,2}|m{1,2}|s{1,2}|l{1}|c{1}|t{1,2}|z|'.*?')/g),
              c = {
            h: -1,
            m: -1,
            s: -1,
            l: -1,
            c: -1,
            t: -1,
            z: -1
          };
          if (b) for (var d = 0; d < b.length; d++) -1 === c[b[d].toString().charAt(0)] && (c[b[d].toString().charAt(0)] = d + 1);
          return c;
        },
            g = "^" + a.toString().replace(/([hH]{1,2}|mm?|ss?|[tT]{1,2}|[zZ]|[lc]|'.*?')/g, function (a) {
          var b = a.length;

          switch (a.charAt(0).toLowerCase()) {
            case "h":
              return 1 === b ? "(\\d?\\d)" : "(\\d{" + b + "})";

            case "m":
              return 1 === b ? "(\\d?\\d)" : "(\\d{" + b + "})";

            case "s":
              return 1 === b ? "(\\d?\\d)" : "(\\d{" + b + "})";

            case "l":
              return "(\\d?\\d?\\d)";

            case "c":
              return "(\\d?\\d?\\d)";

            case "z":
              return "(z|[-+]\\d\\d:?\\d\\d|\\S+)?";

            case "t":
              return e(c.amNames, c.pmNames);

            default:
              return "(" + a.replace(/\'/g, "").replace(/(\.|\$|\^|\\|\/|\(|\)|\[|\]|\?|\+|\*)/g, function (a) {
                return "\\" + a;
              }) + ")?";
          }
        }).replace(/\s/g, "\\s?") + c.timeSuffix + "$",
            h = f(a),
            i = "";

        d = b.match(new RegExp(g, "i"));
        var j = {
          hour: 0,
          minute: 0,
          second: 0,
          millisec: 0,
          microsec: 0
        };
        return d ? (-1 !== h.t && (void 0 === d[h.t] || 0 === d[h.t].length ? (i = "", j.ampm = "") : (i = -1 !== $.inArray(d[h.t].toUpperCase(), $.map(c.amNames, function (a, b) {
          return a.toUpperCase();
        })) ? "AM" : "PM", j.ampm = c["AM" === i ? "amNames" : "pmNames"][0])), -1 !== h.h && ("AM" === i && "12" === d[h.h] ? j.hour = 0 : "PM" === i && "12" !== d[h.h] ? j.hour = parseInt(d[h.h], 10) + 12 : j.hour = Number(d[h.h])), -1 !== h.m && (j.minute = Number(d[h.m])), -1 !== h.s && (j.second = Number(d[h.s])), -1 !== h.l && (j.millisec = Number(d[h.l])), -1 !== h.c && (j.microsec = Number(d[h.c])), -1 !== h.z && void 0 !== d[h.z] && (j.timezone = $.timepicker.timezoneOffsetNumber(d[h.z])), j) : !1;
      }),
          f = function (a, b, c) {
        try {
          var d = new Date("2012-01-01 " + b);
          if (isNaN(d.getTime()) && (d = new Date("2012-01-01T" + b), isNaN(d.getTime()) && (d = new Date("01/01/2012 " + b), isNaN(d.getTime())))) throw "Unable to parse time with native Date: " + b;
          return {
            hour: d.getHours(),
            minute: d.getMinutes(),
            second: d.getSeconds(),
            millisec: d.getMilliseconds(),
            microsec: d.getMicroseconds(),
            timezone: -1 * d.getTimezoneOffset()
          };
        } catch (f) {
          try {
            return e(a, b, c);
          } catch (g) {
            $.timepicker.log("Unable to parse \ntimeString: " + b + "\ntimeFormat: " + a);
          }
        }

        return !1;
      };

      return "function" == typeof d.parse ? d.parse(a, b, d) : "loose" === d.parse ? f(a, b, d) : e(a, b, d);
    }, $.datepicker.formatTime = function (a, b, c) {
      c = c || {}, c = $.extend({}, $.timepicker._defaults, c), b = $.extend({
        hour: 0,
        minute: 0,
        second: 0,
        millisec: 0,
        microsec: 0,
        timezone: null
      }, b);
      var d = a,
          e = c.amNames[0],
          f = parseInt(b.hour, 10);
      return f > 11 && (e = c.pmNames[0]), d = d.replace(/(?:HH?|hh?|mm?|ss?|[tT]{1,2}|[zZ]|[lc]|'.*?')/g, function (a) {
        switch (a) {
          case "HH":
            return ("0" + f).slice(-2);

          case "H":
            return f;

          case "hh":
            return ("0" + convert24to12(f)).slice(-2);

          case "h":
            return convert24to12(f);

          case "mm":
            return ("0" + b.minute).slice(-2);

          case "m":
            return b.minute;

          case "ss":
            return ("0" + b.second).slice(-2);

          case "s":
            return b.second;

          case "l":
            return ("00" + b.millisec).slice(-3);

          case "c":
            return ("00" + b.microsec).slice(-3);

          case "z":
            return $.timepicker.timezoneOffsetString(null === b.timezone ? c.timezone : b.timezone, !1);

          case "Z":
            return $.timepicker.timezoneOffsetString(null === b.timezone ? c.timezone : b.timezone, !0);

          case "T":
            return e.charAt(0).toUpperCase();

          case "TT":
            return e.toUpperCase();

          case "t":
            return e.charAt(0).toLowerCase();

          case "tt":
            return e.toLowerCase();

          default:
            return a.replace(/'/g, "");
        }
      });
    }, $.datepicker._base_selectDate = $.datepicker._selectDate, $.datepicker._selectDate = function (a, b) {
      var c,
          d = this._getInst($(a)[0]),
          e = this._get(d, "timepicker");

      e && d.settings.showTimepicker ? (e._limitMinMaxDateTime(d, !0), c = d.inline, d.inline = d.stay_open = !0, this._base_selectDate(a, b), d.inline = c, d.stay_open = !1, this._notifyChange(d), this._updateDatepicker(d)) : this._base_selectDate(a, b);
    }, $.datepicker._base_updateDatepicker = $.datepicker._updateDatepicker, $.datepicker._updateDatepicker = function (a) {
      var b = a.input[0];

      if (!($.datepicker._curInst && $.datepicker._curInst !== a && $.datepicker._datepickerShowing && $.datepicker._lastInput !== b || "boolean" == typeof a.stay_open && a.stay_open !== !1)) {
        this._base_updateDatepicker(a);

        var c = this._get(a, "timepicker");

        c && c._addTimePicker(a);
      }
    }, $.datepicker._base_doKeyPress = $.datepicker._doKeyPress, $.datepicker._doKeyPress = function (a) {
      var b = $.datepicker._getInst(a.target),
          c = $.datepicker._get(b, "timepicker");

      if (c && $.datepicker._get(b, "constrainInput")) {
        var d = c.support.ampm,
            e = null !== c._defaults.showTimezone ? c._defaults.showTimezone : c.support.timezone,
            f = $.datepicker._possibleChars($.datepicker._get(b, "dateFormat")),
            g = c._defaults.timeFormat.toString().replace(/[hms]/g, "").replace(/TT/g, d ? "APM" : "").replace(/Tt/g, d ? "AaPpMm" : "").replace(/tT/g, d ? "AaPpMm" : "").replace(/T/g, d ? "AP" : "").replace(/tt/g, d ? "apm" : "").replace(/t/g, d ? "ap" : "") + " " + c._defaults.separator + c._defaults.timeSuffix + (e ? c._defaults.timezoneList.join("") : "") + c._defaults.amNames.join("") + c._defaults.pmNames.join("") + f,
            h = String.fromCharCode(void 0 === a.charCode ? a.keyCode : a.charCode);

        return a.ctrlKey || " " > h || !f || g.indexOf(h) > -1;
      }

      return $.datepicker._base_doKeyPress(a);
    }, $.datepicker._base_updateAlternate = $.datepicker._updateAlternate, $.datepicker._updateAlternate = function (a) {
      var b = this._get(a, "timepicker");

      if (b) {
        var c = b._defaults.altField;

        if (c) {
          var d = (b._defaults.altFormat || b._defaults.dateFormat, this._getDate(a)),
              e = $.datepicker._getFormatConfig(a),
              f = "",
              g = b._defaults.altSeparator ? b._defaults.altSeparator : b._defaults.separator,
              h = b._defaults.altTimeSuffix ? b._defaults.altTimeSuffix : b._defaults.timeSuffix,
              i = null !== b._defaults.altTimeFormat ? b._defaults.altTimeFormat : b._defaults.timeFormat;

          f += $.datepicker.formatTime(i, b, b._defaults) + h, b._defaults.timeOnly || b._defaults.altFieldTimeOnly || null === d || (f = b._defaults.altFormat ? $.datepicker.formatDate(b._defaults.altFormat, d, e) + g + f : b.formattedDate + g + f), $(c).val(a.input.val() ? f : "");
        }
      } else $.datepicker._base_updateAlternate(a);
    }, $.datepicker._base_doKeyUp = $.datepicker._doKeyUp, $.datepicker._doKeyUp = function (a) {
      var b = $.datepicker._getInst(a.target),
          c = $.datepicker._get(b, "timepicker");

      if (c && c._defaults.timeOnly && b.input.val() !== b.lastVal) try {
        $.datepicker._updateDatepicker(b);
      } catch (d) {
        $.timepicker.log(d);
      }
      return $.datepicker._base_doKeyUp(a);
    }, $.datepicker._base_gotoToday = $.datepicker._gotoToday, $.datepicker._gotoToday = function (a) {
      var b = this._getInst($(a)[0]);

      this._base_gotoToday(a);

      var c = this._get(b, "timepicker");

      if (c) {
        var d = $.timepicker.timezoneOffsetNumber(c.timezone),
            e = new Date();
        e.setMinutes(e.getMinutes() + e.getTimezoneOffset() + parseInt(d, 10)), this._setTime(b, e), this._setDate(b, e), c._onSelectHandler();
      }
    }, $.datepicker._disableTimepickerDatepicker = function (a) {
      var b = this._getInst(a);

      if (b) {
        var c = this._get(b, "timepicker");

        $(a).datepicker("getDate"), c && (b.settings.showTimepicker = !1, c._defaults.showTimepicker = !1, c._updateDateTime(b));
      }
    }, $.datepicker._enableTimepickerDatepicker = function (a) {
      var b = this._getInst(a);

      if (b) {
        var c = this._get(b, "timepicker");

        $(a).datepicker("getDate"), c && (b.settings.showTimepicker = !0, c._defaults.showTimepicker = !0, c._addTimePicker(b), c._updateDateTime(b));
      }
    }, $.datepicker._setTime = function (a, b) {
      var c = this._get(a, "timepicker");

      if (c) {
        var d = c._defaults;
        c.hour = b ? b.getHours() : d.hour, c.minute = b ? b.getMinutes() : d.minute, c.second = b ? b.getSeconds() : d.second, c.millisec = b ? b.getMilliseconds() : d.millisec, c.microsec = b ? b.getMicroseconds() : d.microsec, c._limitMinMaxDateTime(a, !0), c._onTimeChange(), c._updateDateTime(a);
      }
    }, $.datepicker._setTimeDatepicker = function (a, b, c) {
      var d = this._getInst(a);

      if (d) {
        var e = this._get(d, "timepicker");

        if (e) {
          this._setDateFromField(d);

          var f;
          b && ("string" == typeof b ? (e._parseTime(b, c), f = new Date(), f.setHours(e.hour, e.minute, e.second, e.millisec), f.setMicroseconds(e.microsec)) : (f = new Date(b.getTime()), f.setMicroseconds(b.getMicroseconds())), "Invalid Date" === f.toString() && (f = void 0), this._setTime(d, f));
        }
      }
    }, $.datepicker._base_setDateDatepicker = $.datepicker._setDateDatepicker, $.datepicker._setDateDatepicker = function (a, b) {
      var c = this._getInst(a),
          d = b;

      if (c) {
        "string" == typeof b && (d = new Date(b), d.getTime() || (this._base_setDateDatepicker.apply(this, arguments), d = $(a).datepicker("getDate")));

        var e,
            f = this._get(c, "timepicker");

        d instanceof Date ? (e = new Date(d.getTime()), e.setMicroseconds(d.getMicroseconds())) : e = d, f && e && (f.support.timezone || null !== f._defaults.timezone || (f.timezone = -1 * e.getTimezoneOffset()), d = $.timepicker.timezoneAdjust(d, $.timepicker.timezoneOffsetString(-d.getTimezoneOffset()), f.timezone), e = $.timepicker.timezoneAdjust(e, $.timepicker.timezoneOffsetString(-e.getTimezoneOffset()), f.timezone)), this._updateDatepicker(c), this._base_setDateDatepicker.apply(this, arguments), this._setTimeDatepicker(a, e, !0);
      }
    }, $.datepicker._base_getDateDatepicker = $.datepicker._getDateDatepicker, $.datepicker._getDateDatepicker = function (a, b) {
      var c = this._getInst(a);

      if (c) {
        var d = this._get(c, "timepicker");

        if (d) {
          void 0 === c.lastVal && this._setDateFromField(c, b);

          var e = this._getDate(c),
              f = null;

          return f = d.$altInput && d._defaults.altFieldTimeOnly ? d.$input.val() + " " + d.$altInput.val() : "INPUT" !== d.$input.get(0).tagName && d.$altInput ? d.$altInput.val() : d.$input.val(), e && d._parseTime(f, !c.settings.timeOnly) && (e.setHours(d.hour, d.minute, d.second, d.millisec), e.setMicroseconds(d.microsec), null != d.timezone && (d.support.timezone || null !== d._defaults.timezone || (d.timezone = -1 * e.getTimezoneOffset()), e = $.timepicker.timezoneAdjust(e, d.timezone, $.timepicker.timezoneOffsetString(-e.getTimezoneOffset())))), e;
        }

        return this._base_getDateDatepicker(a, b);
      }
    }, $.datepicker._base_parseDate = $.datepicker.parseDate, $.datepicker.parseDate = function (a, b, c) {
      var d;

      try {
        d = this._base_parseDate(a, b, c);
      } catch (e) {
        if (!(e.indexOf(":") >= 0)) throw e;
        d = this._base_parseDate(a, b.substring(0, b.length - (e.length - e.indexOf(":") - 2)), c), $.timepicker.log("Error parsing the date string: " + e + "\ndate string = " + b + "\ndate format = " + a);
      }

      return d;
    }, $.datepicker._base_formatDate = $.datepicker._formatDate, $.datepicker._formatDate = function (a, b, c, d) {
      var e = this._get(a, "timepicker");

      return e ? (e._updateDateTime(a), e.$input.val()) : this._base_formatDate(a);
    }, $.datepicker._base_optionDatepicker = $.datepicker._optionDatepicker, $.datepicker._optionDatepicker = function (a, b, c) {
      var d,
          e = this._getInst(a);

      if (!e) return null;

      var f = this._get(e, "timepicker");

      if (f) {
        var g,
            h,
            i,
            j,
            k = null,
            l = null,
            m = null,
            n = f._defaults.evnts,
            o = {};

        if ("string" == typeof b) {
          if ("minDate" === b || "minDateTime" === b) k = c;else if ("maxDate" === b || "maxDateTime" === b) l = c;else if ("onSelect" === b) m = c;else if (n.hasOwnProperty(b)) {
            if ("undefined" == typeof c) return n[b];
            o[b] = c, d = {};
          }
        } else if ("object" == typeof b) {
          b.minDate ? k = b.minDate : b.minDateTime ? k = b.minDateTime : b.maxDate ? l = b.maxDate : b.maxDateTime && (l = b.maxDateTime);

          for (g in n) n.hasOwnProperty(g) && b[g] && (o[g] = b[g]);
        }

        for (g in o) o.hasOwnProperty(g) && (n[g] = o[g], d || (d = $.extend({}, b)), delete d[g]);

        if (d && isEmptyObject(d)) return;
        if (k ? (k = 0 === k ? new Date() : new Date(k), f._defaults.minDate = k, f._defaults.minDateTime = k) : l ? (l = 0 === l ? new Date() : new Date(l), f._defaults.maxDate = l, f._defaults.maxDateTime = l) : m && (f._defaults.onSelect = m), k || l) return j = $(a), i = j.datetimepicker("getDate"), h = this._base_optionDatepicker.call($.datepicker, a, d || b, c), j.datetimepicker("setDate", i), h;
      }

      return void 0 === c ? this._base_optionDatepicker.call($.datepicker, a, b) : this._base_optionDatepicker.call($.datepicker, a, d || b, c);
    };

    var isEmptyObject = function (a) {
      var b;

      for (b in a) if (a.hasOwnProperty(b)) return !1;

      return !0;
    },
        extendRemove = function (a, b) {
      $.extend(a, b);

      for (var c in b) (null === b[c] || void 0 === b[c]) && (a[c] = b[c]);

      return a;
    },
        detectSupport = function (a) {
      var b = a.replace(/'.*?'/g, "").toLowerCase(),
          c = function (a, b) {
        return -1 !== a.indexOf(b) ? !0 : !1;
      };

      return {
        hour: c(b, "h"),
        minute: c(b, "m"),
        second: c(b, "s"),
        millisec: c(b, "l"),
        microsec: c(b, "c"),
        timezone: c(b, "z"),
        ampm: c(b, "t") && c(a, "h"),
        iso8601: c(a, "Z")
      };
    },
        convert24to12 = function (a) {
      return a %= 12, 0 === a && (a = 12), String(a);
    },
        computeEffectiveSetting = function (a, b) {
      return a && a[b] ? a[b] : $.timepicker._defaults[b];
    },
        splitDateTime = function (a, b) {
      var c = computeEffectiveSetting(b, "separator"),
          d = computeEffectiveSetting(b, "timeFormat"),
          e = d.split(c),
          f = e.length,
          g = a.split(c),
          h = g.length;
      return h > 1 ? {
        dateString: g.splice(0, h - f).join(c),
        timeString: g.splice(0, f).join(c)
      } : {
        dateString: a,
        timeString: ""
      };
    },
        parseDateTimeInternal = function (a, b, c, d, e) {
      var f, g, h;
      if (g = splitDateTime(c, e), f = $.datepicker._base_parseDate(a, g.dateString, d), "" === g.timeString) return {
        date: f
      };
      if (h = $.datepicker.parseTime(b, g.timeString, e), !h) throw "Wrong time format";
      return {
        date: f,
        timeObj: h
      };
    },
        selectLocalTimezone = function (a, b) {
      if (a && a.timezone_select) {
        var c = b || new Date();
        a.timezone_select.val(-c.getTimezoneOffset());
      }
    };

    $.timepicker = new Timepicker(), $.timepicker.timezoneOffsetString = function (a, b) {
      if (isNaN(a) || a > 840 || -720 > a) return a;
      var c = a,
          d = c % 60,
          e = (c - d) / 60,
          f = b ? ":" : "",
          g = (c >= 0 ? "+" : "-") + ("0" + Math.abs(e)).slice(-2) + f + ("0" + Math.abs(d)).slice(-2);
      return "+00:00" === g ? "Z" : g;
    }, $.timepicker.timezoneOffsetNumber = function (a) {
      var b = a.toString().replace(":", "");
      return "Z" === b.toUpperCase() ? 0 : /^(\-|\+)\d{4}$/.test(b) ? ("-" === b.substr(0, 1) ? -1 : 1) * (60 * parseInt(b.substr(1, 2), 10) + parseInt(b.substr(3, 2), 10)) : parseInt(a, 10);
    }, $.timepicker.timezoneAdjust = function (a, b, c) {
      var d = $.timepicker.timezoneOffsetNumber(b),
          e = $.timepicker.timezoneOffsetNumber(c);
      return isNaN(e) || a.setMinutes(a.getMinutes() + -d - -e), a;
    }, $.timepicker.timeRange = function (a, b, c) {
      return $.timepicker.handleRange("timepicker", a, b, c);
    }, $.timepicker.datetimeRange = function (a, b, c) {
      $.timepicker.handleRange("datetimepicker", a, b, c);
    }, $.timepicker.dateRange = function (a, b, c) {
      $.timepicker.handleRange("datepicker", a, b, c);
    }, $.timepicker.handleRange = function (a, b, c, d) {
      function e(e, f) {
        var g = b[a]("getDate"),
            h = c[a]("getDate"),
            i = e[a]("getDate");

        if (null !== g) {
          var j = new Date(g.getTime()),
              k = new Date(g.getTime());
          j.setMilliseconds(j.getMilliseconds() + d.minInterval), k.setMilliseconds(k.getMilliseconds() + d.maxInterval), d.minInterval > 0 && j > h ? c[a]("setDate", j) : d.maxInterval > 0 && h > k ? c[a]("setDate", k) : g > h && f[a]("setDate", i);
        }
      }

      function f(b, c, e) {
        if (b.val()) {
          var f = b[a].call(b, "getDate");
          null !== f && d.minInterval > 0 && ("minDate" === e && f.setMilliseconds(f.getMilliseconds() + d.minInterval), "maxDate" === e && f.setMilliseconds(f.getMilliseconds() - d.minInterval)), f.getTime && c[a].call(c, "option", e, f);
        }
      }

      d = $.extend({}, {
        minInterval: 0,
        maxInterval: 0,
        start: {},
        end: {}
      }, d);
      var g = !1;
      return "timepicker" === a && (g = !0, a = "datetimepicker"), $.fn[a].call(b, $.extend({
        timeOnly: g,
        onClose: function (a, b) {
          e($(this), c);
        },
        onSelect: function (a) {
          f($(this), c, "minDate");
        }
      }, d, d.start)), $.fn[a].call(c, $.extend({
        timeOnly: g,
        onClose: function (a, c) {
          e($(this), b);
        },
        onSelect: function (a) {
          f($(this), b, "maxDate");
        }
      }, d, d.end)), e(b, c), f(b, c, "minDate"), f(c, b, "maxDate"), $([b.get(0), c.get(0)]);
    }, $.timepicker.log = function () {
      window.console && window.console.log && window.console.log.apply && window.console.log.apply(window.console, Array.prototype.slice.call(arguments));
    }, $.timepicker._util = {
      _extendRemove: extendRemove,
      _isEmptyObject: isEmptyObject,
      _convert24to12: convert24to12,
      _detectSupport: detectSupport,
      _selectLocalTimezone: selectLocalTimezone,
      _computeEffectiveSetting: computeEffectiveSetting,
      _splitDateTime: splitDateTime,
      _parseDateTimeInternal: parseDateTimeInternal
    }, Date.prototype.getMicroseconds || (Date.prototype.microseconds = 0, Date.prototype.getMicroseconds = function () {
      return this.microseconds;
    }, Date.prototype.setMicroseconds = function (a) {
      return this.setMilliseconds(this.getMilliseconds() + Math.floor(a / 1e3)), this.microseconds = a % 1e3, this;
    }), $.timepicker.version = "1.6.3";
  }
});

let $ = (jquery__WEBPACK_IMPORTED_MODULE_0___default());
function core() {
  var $doc = $(document),
      $body = $('body');
  init();
  /**
   * Initialize our dismiss
   * Add our events
  *
  * @since 1.0
   */

  function init() {
    $('.courier-help').tooltip();
    $('[data-equalizer]').each(function () {
      $(this).equalize();
    });
  }
}

$.fn.equalize = function () {
  let equalize_items = $('[data-equalizer-watch]', this),
      equalize_height = 0;
  equalize_items.each(function () {
    equalize_height = $(this).height() > equalize_height ? $(this).height() : equalize_height;
  }).height(equalize_height);
  return this;
};

/***/ }),

/***/ "./assets/js/admin/edit.js":
/*!*********************************!*\
  !*** ./assets/js/admin/edit.js ***!
  \*********************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": function() { return /* binding */ edit; }
/* harmony export */ });
/* harmony import */ var jquery__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! jquery */ "jquery");
/* harmony import */ var jquery__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(jquery__WEBPACK_IMPORTED_MODULE_0__);

let $ = (jquery__WEBPACK_IMPORTED_MODULE_0___default());
function edit() {
  let $doc = $(document),
      $body = $('body');
  init();
  /**
   * Initialize our dismiss
   * Add our events
   *
   * @since 1.0
   */

  function init() {
    $('#courier_expire_date').datetimepicker({
      minDate: 0,
      controlType: 'select',
      timeFormat: 'hh:mm tt',
      dateFormat: 'MM dd, yy',
      stepMinute: 5,
      oneLine: true,
      // firstDay: 0,
      afterInject: function () {
        $('button.ui-datepicker-current').addClass('button button-secondary');
        $('button.ui-datepicker-close').addClass('right button button-primary');
      }
    });

    if ('courier_notice' === courier_notices_admin_data.post_type) {
      $doc.on('ready', populate_status);
    }

    show_hide_type();
    modal_option_rules();
    $body.on('click', '.courier-reactivate-notice', reactivate_notice).on('click', '.copy-text', copy_text).on('change', '#courier_style', show_hide_type).on('change', '#courier_placement_display', change_placement).on('change', '#courier_style', toggle_notice_style_options).on('focus', '#courier-shortcode', function () {
      $('#courier-shortcode').select();
    });
  }
  /**
   * Show or hide different options based on notice style
   *
   * @since 1.3.0
   */


  function toggle_notice_style_options(event) {
    modal_option_rules(event);
    toggle_show_hide_title_options(event);
  }
  /**
   * Show/Hde title controls.
   * Display is dependant on global settings per notice and individual Courier Notice meta
   *
   * @since 1.3.0
   *
   * @param event
   */


  function toggle_show_hide_title_options(event) {
    let $this = $('#courier_style'),
        $selected_option = $this.find('option:selected'),
        val = $selected_option.val(),
        label = $selected_option.text(),
        $show_hide_info = $('#show-hide-info'),
        $selected_style = $('#selected-courier-notice-type'),
        enabled_style_titles = $selected_style.attr('data-enable-title');
    $selected_style.text(label);
    $('.show-hide-toggle').removeClass('hide').hide();

    if (enabled_style_titles.indexOf(val) === -1) {
      $show_hide_info.hide();
      $('#show-title-toggle').show();
    } else {
      $show_hide_info.show();
      $('#hide-title-toggle').show();
    }
  }
  /**
   * Show / Hide rules for Modals
   *
   * @since 1.1
   */


  function modal_option_rules(event) {
    show_hide_placement();
    force_dismissible();
  }
  /**
   * When showing a modal notice, force the notice to be dismissible
   *
   * @since 1.1
   */


  function force_dismissible() {
    let $this = $('#courier_style');

    if ('popup-modal' === $this.find('option:selected').val()) {
      $('#courier_dismissible').prop('checked', 'checked').addClass('disabled').on('click', function (event) {
        event.stopImmediatePropagation();
        event.preventDefault();
      });
    } else {
      $('#courier_dismissible').removeClass('disabled').off('click');
    }
  }
  /**
   * Show or hide the "type" dropdown depending on the style of notice
   * Typically show the type if it's informational
   *
   * @since 1.1
   */


  function show_hide_type(event) {
    let $this = $('#courier_style');

    if ($this.find('option:selected').val() !== 'informational') {
      $('#courier-notice_type_container').hide();
    } else {
      $('#courier-notice_type_container').show();
    }
  }
  /**
   * Show or hide the "placement" dropdown depending on the style of notice
   * Typically we wouldn't show this for popover/modal notices
   *
   * @since 1.1
   */


  function show_hide_placement(event) {
    let $this = $('#courier_style');

    if ($this.find('option:selected').val() === 'popup-modal') {
      $('#courier_placement').val('popup-modal');
      $('#courier-notice_placement_container').hide();
    } else {
      $('#courier-notice_placement_container').show();
    }
  }
  /**
   * Show or hide the "placement" dropdown depending on the style of notice
   * Typically we wouldn't show this for popover/modal notices
   *
   * @since 1.1
   */


  function change_placement(event) {
    let $this = $('#courier_placement_display');
    $('#courier_placement').val($this.val());
  }
  /**
   * When the page loads, push our custom post status into the post status select.
   * If that is the current status of the post, select it and push the text to the on screen label.
   *
   * @since 1.0
   */


  function populate_status() {
    var $option = $('<option />').val('courier_expired').text(courier_notices_admin_data.strings.label);

    if (courier_notices_admin_data.post_status === 'courier_expired') {
      $('#post-status-display').text(courier_notices_admin_data.strings.expired);
      $option.attr('selected', 'selected');
    }

    $('#post_status').append($option);
  }
  /**
   * Reactivate a notice.
   * @param event
   */


  function reactivate_notice(event) {
    event.preventDefault();
    event.stopPropagation();
    let $this = $(this),
        notice_id = $this.attr('data-courier-notice-id'),
        $notice = $this.parents('.notice');
    $.post(courier_notices_admin_data.reactivate_endpoint + notice_id + '/', {
      success: function (data) {
        $notice.fadeOut();
      }
    });
  }
  /**
   * Copy the shortcode
   *
   * @param event
   */


  function copy_text(event) {
    event.preventDefault();
    let $this = $(this),
        $copyID = $this.data('copy'),
        $copy = $('#' + $copyID),
        $indicator = $('.copy-link-indicator'),
        UA = navigator.userAgent,
        isIE = !window.ActiveXObject && "ActiveXObject" in window || UA.indexOf('MSIE') != -1;
    let copyURL = '';

    if (!isIE) {
      $copy.select();

      try {
        var success = document.execCommand('copy');

        if (success) {
          copyURL = true;
        }

        if (!success) {
          copyURL = prompt(courier_notices_admin_data.strings.copy, $copy.text());
        }
      } catch (err) {
        copyURL = prompt(courier_notices_admin_data.strings.copy, $copy.text());
      }
    } else {
      copyURL = prompt(courier_notices_admin_data.strings.copy, $copy.text());
    }

    if (copyURL) {
      $indicator.text(courier_notices_admin_data.strings.copied).fadeIn();
      setTimeout(function () {
        $indicator.fadeOut(function () {
          $indicator.text('');
        });
      }, 3000);
    }
  }
}

/***/ }),

/***/ "./assets/js/admin/list.js":
/*!*********************************!*\
  !*** ./assets/js/admin/list.js ***!
  \*********************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": function() { return /* binding */ list; }
/* harmony export */ });
/* harmony import */ var jquery__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! jquery */ "jquery");
/* harmony import */ var jquery__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(jquery__WEBPACK_IMPORTED_MODULE_0__);

let $ = (jquery__WEBPACK_IMPORTED_MODULE_0___default());
function list() {
  var $doc = $(document),
      $body = $('body');
  init();
  /**
   * Initialize our dismiss
   * Add our events
  *
  * @since 1.0
   */

  function init() {
    if ('courier_notice' === courier_notices_admin_data.post_type) {
      $doc.on('ready', populate_status);
    }

    $body.on('click', '.editinline', quick_edit_populate_status).on('click', '.courier-reactivate-notice', reactivate_notice);
  }
  /**
   * When the page loads, push our custom post status into the post status select.
   * If that is the current status of the post, select it and push the text to the on screen label.
  *
  * @since 1.0
   */


  function populate_status() {
    var $option = $('<option />').val('courier_expired').text(courier_notices_admin_data.strings.label);

    if (courier_notices_admin_data.post_status === 'courier_expired') {
      $('#post-status-display').text(courier_notices_admin_data.strings.expired);
      $option.attr('selected', 'selected');
    }

    $('#post_status').append($option);
  }
  /**
   * Puts an Expired option in the quick edit dropdown menu.
  *
  * @since 1.0
   */


  function quick_edit_populate_status() {
    var $this = $(this),
        $row = $this.parents('tr.iedit'),
        post_id = $row.attr('id').replace('post-', ''),
        post_status = $('#inline_' + post_id + ' ._status').text(),
        $edit_row = '',
        $select = '',
        $expired_option = $('<option />').text(courier_notices_admin_data.strings.label).attr('value', 'courier_expired'); // Delay things to ensure the quick edit row has been added to the page.

    setTimeout(function () {
      $edit_row = $('#edit-' + post_id);
      $select = $('#edit-' + post_id + ' select[name="_status"]');
      $select.append($expired_option);
      $select.val(post_status);
    }, 300);
  }
  /**
   * Reactivate a notice.
   * @param event
   */


  function reactivate_notice(event) {
    event.preventDefault();
    event.stopPropagation();
    var $this = $(this),
        notice_id = $this.attr('data-courier-notice-id'),
        $notice = $this.parents('.notice');
    $.post(courier_notices_admin_data.reactivate_endpoint + notice_id + '/', {
      success: function (data) {
        $notice.fadeOut();
      }
    });
  }
}

/***/ }),

/***/ "./assets/js/admin/notifications.js":
/*!******************************************!*\
  !*** ./assets/js/admin/notifications.js ***!
  \******************************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": function() { return /* binding */ notifications; }
/* harmony export */ });
/* harmony import */ var jquery__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! jquery */ "jquery");
/* harmony import */ var jquery__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(jquery__WEBPACK_IMPORTED_MODULE_0__);
/**
 * Controls Notification Administration
 *
 * @since 1.2
 */

/**
 * Controls Welcome area display
 *
 * @package    CourierNotices
 * @subpackage Welcome
 * @since      1.0
 */

let $ = (jquery__WEBPACK_IMPORTED_MODULE_0___default());
function notifications() {
  let $body = $('body');
  init();
  /**
   * Initialize Notifications
   */

  function init() {
    $body.on('click', '.courier-admin-notice .notice-dismiss', dismissNotification).on('click', '.courier-review-notice .review-dismiss, .courier-review-notice .notice-dismiss', dismissNotification);
  }
  /**
   * Ajax call to dismiss notifications.
   *
   * @since 1.2
   */


  function dismissNotification(event) {
    event.preventDefault();
    var $notice = $(this).parents('.courier-admin-notice');
    $.post(ajaxurl, {
      action: 'courier_dismiss_notification',
      courier_notification_type: $notice.attr('data-type'),
      _ajax_nonce: courier_notices_admin_data.dismiss_nonce
    }, function (response) {
      $notice.fadeTo(100, 0, function () {
        $notice.slideUp(100, function () {
          $notice.remove();
        });
      });
    });
  }
}

/***/ }),

/***/ "./assets/js/admin/settings.js":
/*!*************************************!*\
  !*** ./assets/js/admin/settings.js ***!
  \*************************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": function() { return /* binding */ settings; }
/* harmony export */ });
/* harmony import */ var jquery__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! jquery */ "jquery");
/* harmony import */ var jquery__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(jquery__WEBPACK_IMPORTED_MODULE_0__);

let $ = (jquery__WEBPACK_IMPORTED_MODULE_0___default());
function settings() {
  // Extend Serialize Array to also include checkboxes that are not selected
  (function ($) {
    let _base_serializeArray = $.fn.serializeArray;
    /**
     * extract the field name based on key
     *
     * @param fieldName
     * @return {*}
     */

    function cleanupFieldName(fieldName) {
      let matches = fieldName.match(/\[([a-zA-Z_]*)\]/);

      if (matches) {
        return matches[1];
      }

      return fieldName;
    }

    $.fn.serializeArray = function () {
      let originalArray = _base_serializeArray.apply(this);

      let dataCleanup = {};
      $.each(this.find("input"), function (i, element) {
        if ("checkbox" === element.type || "radio" === element.type) {
          element.checked ? originalArray[i].value = true : originalArray.splice(i, 0, {
            name: element.name,
            value: false
          });
        }

        let fieldName = cleanupFieldName(element.name);

        if (dataCleanup[fieldName] !== undefined) {
          // The field exists, if it's not an array create one
          if (!Array.isArray(dataCleanup[fieldName])) {
            if (false !== dataCleanup[fieldName]) {
              dataCleanup[fieldName] = [dataCleanup[fieldName]];
            } else {
              dataCleanup[fieldName] = [];
            }
          }

          if (false !== element.value && element.checked) {
            dataCleanup[fieldName].push(element.value);
          }
        } else {
          if ("checkbox" === element.type || "radio" === element.type) {
            if (element.checked) {
              if (false !== element.value) {
                dataCleanup[fieldName] = element.value;
              } else {
                dataCleanup[fieldName] = false;
              }
            } else {
              dataCleanup[fieldName] = false;
            }
          } else {
            dataCleanup[fieldName] = element.value;
          }
        }
      });

      for (const field in dataCleanup) {
        if (typeof dataCleanup[field] !== 'object') {
          continue;
        }

        if (0 === dataCleanup[field].length) {
          dataCleanup[field] = false;
        }
      }

      return dataCleanup;
    };
  })((jquery__WEBPACK_IMPORTED_MODULE_0___default()));

  setup_forms();
  /**
   * Convert our form data to a json object
   *
   * @param form
   * @return {{}}
   */

  function formDataToJSON(form) {
    let formData = $(form).serializeArray();
    return formData;
  }
  /**
   * Initialize our dismiss
   * Add our events
  *
  * @since 1.0
   */


  function setup_forms() {
    let $settings_form = $('.courier-notices-settings-form');
    $settings_form.find('#submit').on('click', function (event) {
      event.preventDefault();
      $(this).attr({
        'disabled': 'disabled',
        'value': 'Saving'
      }).parents('form').submit();
    });
    $settings_form.on('submit', function (event) {
      event.preventDefault();
      event.stopImmediatePropagation();
      let $form = $(this);
      let formData = formDataToJSON(this);
      formData['settings_key'] = formData['option_page'];
      formData['method'] = 'POST';
      formData._wpnonce = courier_notices_admin_data.settings_nonce;
      $.ajax({
        'url': courier_notices_admin_data.settings_endpoint,
        'beforeSend': function (xhr) {
          xhr.setRequestHeader('X-WP-Nonce', formData._wpnonce);
        },
        'method': 'POST',
        'data': formData
      }).done(function (response) {
        $settings_form.find('#submit').attr({
          'value': 'Save Changes'
        }).removeAttr('disabled');
      });
    });
  }
}

/***/ }),

/***/ "./assets/js/admin/types.js":
/*!**********************************!*\
  !*** ./assets/js/admin/types.js ***!
  \**********************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": function() { return /* binding */ types; }
/* harmony export */ });
/* harmony import */ var jquery__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! jquery */ "jquery");
/* harmony import */ var jquery__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(jquery__WEBPACK_IMPORTED_MODULE_0__);
/**
 * Controls Notice Types admin area within settings
 *
 * @package    CourierNotices
 * @subpackage Types
 * @since      1.0
 */

let $ = (jquery__WEBPACK_IMPORTED_MODULE_0___default());
function types() {
  // Private Variables
  let $body = $('body'),
      $types = $('.courier_notice_page_courier'),
      $new_container = $('#courier-notice-type-new'),
      $courierTypeColor = $('.courier-type-color'),
      courierNoticeTypeTemplate = $('#courier-notice-type-template').text().split(/\{(.+?)\}/g),
      courierNoticeTypeEditTemplate = $('#courier-notice-type-edit-template').text().split(/\{(.+?)\}/g),
      inputTemplate = {
    indicator: $('#courier-notice-loader').html(),
    cancel: courier_notices_admin_data.strings.cancel,
    cancelcssclass: 'btn btn-danger',
    submitcssclass: 'btn btn-success',
    maxlength: 50,
    showfn: function (elem) {
      elem.fadeIn('fast');
    },
    submit: courier_notices_admin_data.strings.save,
    tooltip: courier_notices_admin_data.strings.editurl,
    width: '100%'
  },
      courierNoticeTypeCurrent = '';
  /**
   * Initialize the module
   *
   * @since 1.0.5
   */

  function init() {
    // Setup type edit screen js within settings.
    setupControls();
  }
  /**
   * Create all the event listeners for the module
   *
   * @since 1.0
   */


  function setupControls() {
    $body.on('click', '.courier-notices-type-delete', confirmDeleteCourierNoticeType).on('click', '.courier-notices-type-cancel-delete', cancelDeleteCourierNoticeType).on('click', '#add-courier-notice-type', addNewCourierNoticeTypeRow).on('click', '#courier-notice-type-new .save-button', addCourierNoticeType).on('click', '#courier-notice-type-new .close-button', cancelAddCourierNoticeType).on('click', '.courier-notice-editing .close-button', cancelEditCourierNoticeType).on('click', '.courier-notice-editing .save-button', updateCourierNoticeType).on('click', '.courier-notice-type-edit', editCourierNoticeType).on('click', '#courier-settings .settings-form #submit', disableTypeControls);
  }
  /**
   * Due to the complexity of this input type, we need to disable all the hidden fields associated with it
   * so they are not saved to the options table.
   *
   * @since 1.0
   *
   * @param event
   */


  function disableTypeControls(event) {
    event.stopPropagation();
    $('table.courier_notice_page_courier').find('input,button').attr('disabled', 'disabled');
    $('#nds-post-body').find('input[type="hidden"]').attr('disabled', 'disabled');
    $('.settings-form').submit();
  }
  /**
   * Edit a courier notice type
   *
   * @since 1.0
   *
   * @param event
   */


  function editCourierNoticeType(event) {
    event.preventDefault();
    var $parentRow = $(this).closest('tr');
    $('table.courier_notice_page_courier .button-editing.close-button:visible').trigger('click');
    $parentRow.addClass('courier-notice-editing').find('.notice-options').show();
    $parentRow.find('.courier-notice-type-title').hide();
    setupTypeEditing($parentRow);
  }
  /**
   * Cancel editing an existing Courier Notice Type.
   *
   * @since 1.0
   *
   * @param event
   */


  function cancelEditCourierNoticeType(event) {
    event.preventDefault();
    var $target = $('#courier-notice-type-edit').replaceWith(courierNoticeTypeCurrent);
    var $parentRow = $(this).closest('tr');
    $('.courier-icon', $parentRow).removeAttr('style');
    $('.courier-content-wrapper', $parentRow).removeAttr('style');
    $('.notice-options', $parentRow).hide();
    $('.courier-notice-type-title', $parentRow).show();
    $('.courier-notice-editing').removeClass('courier-notice-editing');
    $parentRow.find('.courier-type-color').each(function () {
      $(this).val($(this).data('default-color'));
    });
    $parentRow.find('.courier-type-color').trigger('change');
  }
  /**
   * Display the edit template.
   *
   * @since 1.0
   *
   * @param template
   * @param options
   */


  function displayEditTemplate(template, options) {
    // If no template, die early
    if (typeof template === 'undefined') {
      return;
    }

    options = options ? options : {};
    let input = $.extend(true, options, template);
    let $noticeRow = $(courierNoticeTypeEditTemplate.map(render(input)).join(''));
    $('.courier-notice-editing').replaceWith($($noticeRow));
    setupTypeEditing($noticeRow);
  }
  /**
   * Cancel adding a new Courier Notice Type.
   *
   * @since 1.0
   *
   * @param event
   */


  function cancelAddCourierNoticeType(event) {
    event.preventDefault();
    let $target = $('#courier-notice-type-new');
    $target.fadeOut('fast').promise().done(function () {
      $('#add-courier-notice-type').removeAttr('disabled').removeClass('disabled');
      $(this).remove();
    });
  }
  /**
   * Add a new courier type.
   *
   * @since 1.0
   *
   * @param event
   */


  function addNewCourierNoticeTypeRow(event) {
    event.preventDefault();
    $(this).addClass('disabled').attr('disabled', 'disabled'); // Only show the new row if it's not visible.
    // if ( ! $new_container.is(':visible') ) {

    var options = {};
    var input = $.extend(true, options, inputTemplate);
    displayNewCourierNoticeTypeTemplate(input); // }
  }
  /**
   * Confirm delete of Courier Notice type term
   *
   * @todo there should be some sort of busy/loading indicator
   *
   * @since 1.0
   *
   * @param event
   */


  function confirmDeleteCourierNoticeType(event) {
    event.preventDefault();
    var $this = $(this),
        $cancel = $('<button />', {
      'class': 'courier-notices-type-cancel-delete button button-secondary button-cancel button-editing close-button',
      'href': '#'
    }),
        $cancel_icon = $('<span />', {
      'class': 'dashicons dashicons-no'
    });

    if (true !== $this.data('confirm')) {
      // $this.find('dashicons-trash').hide();
      $cancel.append($cancel_icon);
      $this.addClass('button button-secondary button-editing').attr('aria-label', courier_notices_admin_data.strings.confirm_delete).data('confirm', true);
      $this.after($cancel).after('<span class="spacer">&nbsp;</span>');
    } else {
      $this.addClass('disabled').text(courier_notices_admin_data.strings.deleting);
      deleteCourierNoticeType($this);
    }
  }
  /**
   * Delete the Courier Notice Type
   *
   * @since 1.0
   */


  function deleteCourierNoticeType($target) {
    $.post(ajaxurl, {
      action: 'courier_notices_delete_type',
      courier_notices_delete_type: courier_notices_admin_data.delete_nonce,
      courier_notices_type: parseInt($target.data('term-id'))
    }).success(function () {
      $target.closest('tr').fadeOut('fast').promise().done(function () {
        $(this).remove(); // Remove the row from the table after it fades out.
      });
    });
  }
  /**
   * Cancel deleting of the Courier Notice Type
   *
   * @since 1.0
   */


  function cancelDeleteCourierNoticeType(event) {
    event.preventDefault();
    var $this = $(this),
        $spacer = $this.siblings('.spacer'),
        $trash = $this.siblings('.courier-notices-type-delete');
    $this.remove();
    $spacer.remove();
    $trash.removeClass('button button-secondary button-editing').removeAttr('aria-label').data('confirm', false);
  }
  /**
   * Setup Courier Type Editing Screen
   *
   * @since 1.0
   */


  function setupTypeEditing($target) {
    // If we don't have a $target element target all color pickers.
    if (!$target) {
      $courierTypeColor = $('.courier-type-color');
    } else {
      $courierTypeColor = $target.find('.courier-type-color');
    }

    if (!$courierTypeColor.length) {
      return;
    }

    $courierTypeColor.wpColorPicker({
      change: function (event, ui) {
        let $target = $(this).closest('.notice_preview'),
            notice_ui = $(this).closest('.notice-option').data('notice-option-color');
        setTimeout(function () {
          var color_value = event.target.value;

          switch (notice_ui) {
            case 'accent':
              $target.find('.courier-icon').css('background', color_value);
              break;

            case 'icon':
              $target.find('.courier-icon').css('color', color_value);
              break;

            case 'text':
              $target.find('.courier-content-wrapper').css('color', color_value);
              break;

            case 'bg':
              $target.find('.courier-content-wrapper').css('background', color_value);
              break;
          }
        }, 1);
      }
    });
  }
  /**
   * Render our template including our properties.
   *
   * This is a temp solution prior to move to react.
   *
   * @param props
   * @returns {Function}
   */


  function render(props) {
    return function (token, i) {
      return i % 2 ? props[token] : token;
    };
  }
  /**
   * Display the template for adding a new Notice Type.
   * @param item
   */


  function displayNewCourierNoticeTypeTemplate(item) {
    let $noticeRow = $(courierNoticeTypeTemplate.map(render(item)).join(''));
    $noticeRow = $($noticeRow);
    $('table.courier_notice_page_courier tbody').append($noticeRow);
    setupTypeEditing($noticeRow);
  }
  /**
   * Add / Save our new courier notice type
   */


  function addCourierNoticeType(event) {
    event.preventDefault();
    $(this).addClass('disabled').attr('disabled', 'disabled');
    var $target = $('#courier-notice-type-new');
    $.post(ajaxurl, {
      action: 'courier_notices_add_type',
      'page': 'courier',
      'courier_notices_add_type': courier_notices_admin_data.add_nonce,
      'courier_notice_type_new_title': $('#courier-notice-type-new-title').val(),
      'courier_notice_type_new_css_class': $('#courier-notice-type-new-css-class').val(),
      'courier_notice_type_new_color': $('#courier-notice-type-new-color').val(),
      'courier_notice_type_new_text_color': $('#courier-notice-type-new-text-color').val(),
      'courier_notice_type_new_icon_color': $('#courier-notice-type-new-icon-color').val(),
      'courier_notice_type_new_bg_color': $('#courier-notice-type-new-bg-color').val(),
      contentType: "application/json"
    }).success(function (response) {
      response = JSON.parse(response);

      if (response && response.fragments) {
        $target.fadeOut('fast').promise().done(function () {
          $('#add-courier-notice-type').removeAttr('disabled').removeClass('disabled');

          for (const fragment in response.fragments) {
            $(fragment).html(response.fragments[fragment]);
          }
        });
      }
    });
  }
  /**
   * Update courier notice type
   *
   * @since 1.0
   */


  function updateCourierNoticeType(event) {
    event.preventDefault();
    let $this = $(this),
        $target = $this.closest('tr');
    $this.addClass('disabled').attr('disabled', 'disabled');
    $.post(ajaxurl, {
      action: 'courier_notices_update_type',
      'page': 'courier',
      'courier_notices_update_type': courier_notices_admin_data.update_nonce,
      'courier_notice_type_edit_title': $target.find('.courier-notice-type-edit-title').val(),
      'courier_notice_type_edit_css_class': $target.find('.courier-notice-type-edit-css-class').val(),
      'courier_notice_type_edit_color': $target.find('.courier-notice-type-edit-color').val(),
      'courier_notice_type_edit_text_color': $target.find('.courier-notice-type-edit-text-color').val(),
      'courier_notice_type_edit_icon_color': $target.find('.courier-notice-type-edit-icon-color').val(),
      'courier_notice_type_edit_bg_color': $target.find('.courier-notice-type-edit-bg-color').val(),
      'courier_notice_type_id': parseInt($target.find('[data-courier-notice-id]').data('courier-notice-id')),
      'contentType': "application/json"
    }).success(function (response) {
      response = JSON.parse(response);

      if (response && response.fragments) {
        $target.fadeOut('fast').promise().done(function () {
          for (let fragment in response.fragments) {
            $(fragment).html(response.fragments[fragment]);
          }
        });
      }
    });
  }

  init(); // kick everything off controlling types.
}

/***/ }),

/***/ "./assets/js/admin/welcome.js":
/*!************************************!*\
  !*** ./assets/js/admin/welcome.js ***!
  \************************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": function() { return /* binding */ welcome; }
/* harmony export */ });
/* harmony import */ var jquery__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! jquery */ "jquery");
/* harmony import */ var jquery__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(jquery__WEBPACK_IMPORTED_MODULE_0__);
/**
 * Controls Welcome area display
 *
 * @package    CourierNotices
 * @subpackage Welcome
 * @since      1.0
 */

let $ = (jquery__WEBPACK_IMPORTED_MODULE_0___default());
function welcome() {
  // Private Variables
  let $window = $(window),
      $doc = $(document),
      $body = $('body'),
      $welcomePanel = $('#courier-notices-welcome-panel');
  /**
   * Add some event listeners
   */

  function init() {
    $welcomePanel.find('.courier-notices-welcome-panel-close').on('click', function (event) {
      event.preventDefault();
      $welcomePanel.addClass('hidden');
      updateWelcomePanel(0);
    });
  }
  /**
   * Show or Hide our Courier Notices Welcome Panel
   * Based on the Welcome Panel in WP Core
   *
   * @param visible
   */


  function updateWelcomePanel(visible) {
    $.post(ajaxurl, {
      action: 'courier_notices_update_welcome_panel',
      visible: visible,
      courier_notices_welcome_panel: $('#courier_notices_welcome_panel').val()
    });
  }

  init(); // kick everything off for welcome onboarding
}

/***/ }),

/***/ "jquery":
/*!*************************!*\
  !*** external "jQuery" ***!
  \*************************/
/***/ (function(module) {

module.exports = jQuery;

/***/ })

/******/ 	});
/************************************************************************/
/******/ 	// The module cache
/******/ 	var __webpack_module_cache__ = {};
/******/ 	
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/ 		// Check if module is in cache
/******/ 		var cachedModule = __webpack_module_cache__[moduleId];
/******/ 		if (cachedModule !== undefined) {
/******/ 			return cachedModule.exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = __webpack_module_cache__[moduleId] = {
/******/ 			// no module.id needed
/******/ 			// no module.loaded needed
/******/ 			exports: {}
/******/ 		};
/******/ 	
/******/ 		// Execute the module function
/******/ 		__webpack_modules__[moduleId](module, module.exports, __webpack_require__);
/******/ 	
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/ 	
/************************************************************************/
/******/ 	/* webpack/runtime/amd options */
/******/ 	!function() {
/******/ 		__webpack_require__.amdO = {};
/******/ 	}();
/******/ 	
/******/ 	/* webpack/runtime/compat get default export */
/******/ 	!function() {
/******/ 		// getDefaultExport function for compatibility with non-harmony modules
/******/ 		__webpack_require__.n = function(module) {
/******/ 			var getter = module && module.__esModule ?
/******/ 				function() { return module['default']; } :
/******/ 				function() { return module; };
/******/ 			__webpack_require__.d(getter, { a: getter });
/******/ 			return getter;
/******/ 		};
/******/ 	}();
/******/ 	
/******/ 	/* webpack/runtime/define property getters */
/******/ 	!function() {
/******/ 		// define getter functions for harmony exports
/******/ 		__webpack_require__.d = function(exports, definition) {
/******/ 			for(var key in definition) {
/******/ 				if(__webpack_require__.o(definition, key) && !__webpack_require__.o(exports, key)) {
/******/ 					Object.defineProperty(exports, key, { enumerable: true, get: definition[key] });
/******/ 				}
/******/ 			}
/******/ 		};
/******/ 	}();
/******/ 	
/******/ 	/* webpack/runtime/hasOwnProperty shorthand */
/******/ 	!function() {
/******/ 		__webpack_require__.o = function(obj, prop) { return Object.prototype.hasOwnProperty.call(obj, prop); }
/******/ 	}();
/******/ 	
/******/ 	/* webpack/runtime/make namespace object */
/******/ 	!function() {
/******/ 		// define __esModule on exports
/******/ 		__webpack_require__.r = function(exports) {
/******/ 			if(typeof Symbol !== 'undefined' && Symbol.toStringTag) {
/******/ 				Object.defineProperty(exports, Symbol.toStringTag, { value: 'Module' });
/******/ 			}
/******/ 			Object.defineProperty(exports, '__esModule', { value: true });
/******/ 		};
/******/ 	}();
/******/ 	
/************************************************************************/
var __webpack_exports__ = {};
// This entry need to be wrapped in an IIFE because it need to be isolated against other modules in the chunk.
!function() {
/*!********************************************!*\
  !*** ./assets/js/courier-notices-admin.js ***!
  \********************************************/
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var jquery__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! jquery */ "jquery");
/* harmony import */ var jquery__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(jquery__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _admin_core__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./admin/core */ "./assets/js/admin/core.js");
/* harmony import */ var _admin_welcome__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./admin/welcome */ "./assets/js/admin/welcome.js");
/* harmony import */ var _admin_types__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ./admin/types */ "./assets/js/admin/types.js");
/* harmony import */ var _admin_edit__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ./admin/edit */ "./assets/js/admin/edit.js");
/* harmony import */ var _admin_list__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! ./admin/list */ "./assets/js/admin/list.js");
/* harmony import */ var _admin_settings__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(/*! ./admin/settings */ "./assets/js/admin/settings.js");
/* harmony import */ var _admin_notifications__WEBPACK_IMPORTED_MODULE_7__ = __webpack_require__(/*! ./admin/notifications */ "./assets/js/admin/notifications.js");








jquery__WEBPACK_IMPORTED_MODULE_0___default()(function () {
  (0,_admin_core__WEBPACK_IMPORTED_MODULE_1__["default"])();
  (0,_admin_settings__WEBPACK_IMPORTED_MODULE_6__["default"])();
  (0,_admin_edit__WEBPACK_IMPORTED_MODULE_4__["default"])();
  (0,_admin_list__WEBPACK_IMPORTED_MODULE_5__["default"])();
  (0,_admin_welcome__WEBPACK_IMPORTED_MODULE_2__["default"])();
  (0,_admin_types__WEBPACK_IMPORTED_MODULE_3__["default"])();
  (0,_admin_notifications__WEBPACK_IMPORTED_MODULE_7__["default"])();
});
}();
/******/ })()
;
//# sourceMappingURL=courier-notices-admin.js.map