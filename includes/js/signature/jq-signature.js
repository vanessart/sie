(function(window, document, $) {
	'use strict';
  // Get a regular interval for drawing to the screen
  window.requestAnimFrame = (function (callback) {
    return window.requestAnimationFrame || 
      window.webkitRequestAnimationFrame ||
      window.mozRequestAnimationFrame ||
      window.oRequestAnimationFrame ||
      window.msRequestAnimaitonFrame ||
      function (callback) {
        window.setTimeout(callback, 1000/60);
      };
  })();

  /*
  * Plugin Constructor
  */

    var pluginName = 'jqSignature',
      defaults = {
        lineColor: '#222222',
        lineWidth: 1,
        border: '1px dashed #AAAAAA',
        background: '#FFFFFF',
        width: 300,
        height: 100,
        autoFit: false
      },
      canvasFixture = '<canvas></canvas>';

  function Signature(element, options) {    
    // DOM elements/objects
    this.element = element;
    this.$element = $(this.element);
    this.canvas = false;
    this.$canvas = false;
    this.ctx = false;
    this.disabled = false;
    // Drawing state
    this.drawing = false;
    this.currentPos = {
      x: 0,
      y: 0
    };
    this.lastPos = this.currentPos;
    // Determine plugin settings
    this._data = this.$element.data();
    this.settings = $.extend({}, defaults, options, this._data);
    // Initialize the plugin
    this.init();
  }

  Signature.prototype = {
    // Initialize the signature canvas
    init: function() {
      // Set up the canvas
      this.$canvas = $(canvasFixture).appendTo(this.$element);
      this.$canvas.attr({
        width: this.settings.width,
        height: this.settings.height
      });
      this.$canvas.css({
        boxSizing: 'border-box',
        width: this.settings.width + 'px',
        height: this.settings.height + 'px',
        border: this.settings.border,
        background: this.settings.background,
        cursor: 'crosshair'
      });

      this.$canvas.attr('id', 'TheCanvas');
      // Fit canvas to width of parent
      if (this.settings.autoFit === true) {
        this._resizeCanvas();
        // TO-DO - allow for dynamic canvas resizing 
        // (need to save canvas state before changing width to avoid getting cleared)
        // var timeout = false;
        // $(window).on('resize', $.proxy(function(e) {
        //   clearTimeout(timeout);
        //   timeout = setTimeout($.proxy(this._resizeCanvas, this), 250);
        // }, this));
      }
      this.canvas = this.$canvas[0];
      this._resetCanvas();
        // Set up mouse events

      // IE fix
      /*if (window.PointerEvent) {
          var c = document.getElementById('TheCanvas');
          c.addEventListener("MSPointerUp", $.proxy(function (e) {
                  this.drawing = false;
                  // Trigger a change event
                  var changedEvent = $.Event('jq.signature.changed');
                  this.$element.trigger(changedEvent);
              }, this), false);
          c.addEventListener("MSPointerMove", $.proxy(function (e) {
              this.currentPos = this._getPosition(e);
          }, this), false);
          c.addEventListener("MSPointerDown", $.proxy(function (e) {
                  this.drawing = true;
                  this.lastPos = this.currentPos = this._getPosition(e);
              }, this), false);
      }
      else {*/
          this.$canvas.on('mousedown touchstart', $.proxy(function (e) {
              e.preventDefault();
              this.drawing = true;
              this.lastPos = this.currentPos = this._getPosition(e);
          }, this));
          this.$canvas.on('mousemove touchmove', $.proxy(function (e) {
              e.preventDefault();
              this.currentPos = this._getPosition(e);
          }, this));
          this.$canvas.on('mouseup touchend', $.proxy(function (e) {
              e.preventDefault();
              this.drawing = false;
              // Trigger a change event
              var changedEvent = $.Event('jq.signature.changed');
              this.$element.trigger(changedEvent);
          }, this));
          // Prevent document scrolling when touching canvas
          // $(document).on('touchstart touchmove touchend', $.proxy(function (e) {
          //     if (e.target === this.canvas) {
          //         e.preventDefault();
          //     }
          // }, this));
      //}

      // Start drawing
      var that = this;
      (function drawLoop() {
        window.requestAnimFrame(drawLoop);
        that._renderCanvas();
      })();
    },
    // Clear the canvas
    clearCanvas: function() {
      this.ctx.clearRect(0, 0, this.canvas.width, this.canvas.height);
      this._resetCanvas();
    },
    enableDisableCanvas: function(){
      debugger;
      let t = !this.disabled;
      if (t){
        this.$element.css({'cursor': 'not-allowed', 'pointer-events': 'none', 'opacity': .70});
      } else {
        this.$element.css({'cursor': 'inherit', 'pointer-events': 'all', 'opacity': 1});
      }

      this.$canvas.attr('disabled', t);
      this.disabled = t;
    },
    // Get the content of the canvas as a base64 data URL
    getDataURL: function() {
      return this.canvas.toDataURL();
    },
    // Get the position of the mouse/touch
    _getPosition: function (event) {
      var xPos, yPos, rect;
      rect = this.canvas.getBoundingClientRect();
      if (event.originalEvent)
          event = event.originalEvent;

      // Touch event
      if (event.type.indexOf('touch') !== -1) { // event.constructor === TouchEvent          
        xPos = event.touches[0].clientX - rect.left;
        yPos = event.touches[0].clientY - rect.top;
      }
      // Mouse event
      else {
        xPos = event.clientX - rect.left;
        yPos = event.clientY - rect.top;
      }
      return {
        x: xPos,
        y: yPos
      };
    },
    // Render the signature to the canvas
    _renderCanvas: function() {
        if (this.drawing) {
        this.ctx.beginPath();
        this.ctx.moveTo(this.lastPos.x, this.lastPos.y);
        this.ctx.lineTo(this.currentPos.x, this.currentPos.y);
        this.ctx.stroke();
        this.lastPos = this.currentPos;
      }
    },
    // Reset the canvas context
    _resetCanvas: function() {
      this.ctx = this.canvas.getContext("2d");
      this.ctx.strokeStyle = this.settings.lineColor;
      this.ctx.lineWidth = this.settings.lineWidth;
    },
    // Resize the canvas element
    _resizeCanvas: function() {
      var width = this.$element.outerWidth();
      this.$canvas.attr('width', width);
      this.$canvas.css('width', width + 'px');
    }
  };

  /*
  * Plugin wrapper and initialization
  */

  $.fn[pluginName] = function ( options ) {
    var args = arguments;
    if (options === undefined || typeof options === 'object') {
      return this.each(function () {
        if (!$.data(this, 'plugin_' + pluginName)) {
          $.data(this, 'plugin_' + pluginName, new Signature( this, options ));
        }
      });
    } 
    else if (typeof options === 'string' && options[0] !== '_' && options !== 'init') {
      var returns;
      this.each(function () {
        var instance = $.data(this, 'plugin_' + pluginName);
        if (instance instanceof Signature && typeof instance[options] === 'function') {
          returns = instance[options].apply( instance, Array.prototype.slice.call( args, 1 ) );
        }
        if (options === 'destroy') {
          $.data(this, 'plugin_' + pluginName, null);
        }
      });
      return returns !== undefined ? returns : this;
    }
  };

})(window, document, jQuery);
