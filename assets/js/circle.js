import React from 'react';
import ReactDOM from 'react-dom';
import MarkdownContainer from './react/markdown-container';

var $ = require('jquery');


// Create Position Circle

function Circle(x, y, options) {
  var self = this;
  this.x = x;
  this.y = y;
  this.className = 'position-circle';
  this.ele = document.createElement('div');
  this.ele.style.left = `${this.x - 12}px`;
  this.ele.style.top = `${this.y - 12}px`;

  this.popLayer = $('#myModal');
  this.ele.setAttribute('class', this.className);
  this.ele.setAttribute('data-toggle', 'modal');
  this.ele.setAttribute('data-target', '#myModal');
  this.ele.addEventListener('click', function () {
    window.currentCicle = self;
    var id = parseInt(this.getAttribute('data-id'));
    for(var i = 0; i < window.allCircle.length; i++) {
      if (window.allCircle[i].id == id) {
        ReactDOM.render(
          <MarkdownContainer data={window.allCircle[i]} />,
          $('#template-tab-content-container')[0]
        );
      }
    }
    if (options && options.callback) {
      options.callback();
    }
  });

  $(window).scroll(function() {
    var top = $(window).scrollTop();
    var left = $(window).scrollLeft();
    $(self.ele).css('top', parseInt(self.y) - 12 - top);
    $(self.ele).css('left', parseInt(self.x) - 12 - left);
  });

  if (options) {
    this.ele.setAttribute('data-id', options.id);
    this.id = options.id;
  }
  document.body.appendChild(this.ele);
}

Circle.prototype.importData = function (data) {
  if (!data) {
    data = window.allCircle;
  }
  // console.log(data);
};

Circle.prototype.storeCircle = function () {
  $.ajax({
    url: '/data/point/insert',
    type: 'POST',
    data: {
      map_page_sub_id: map_page_sub_id,
      point_x: this.x,
      point_y: this.y
    },
    success: function (e) {
      // console.log(e);
    }
  });
};

Circle.prototype.deleteCircle = function (callback) {
  const self = this;
  $.ajax({
    url: '/data/point/delete',
    type: 'POST',
    data: {
      id: this.id
    },
    success: function (e) {
      switch (e.code.toString()) {
        default:
          break;
        case '00000':
          self.popLayer.modal('hide');
          if (callback) {
            callback();
          }
          break;
      }
    }
  });
};

module.exports = Circle;
