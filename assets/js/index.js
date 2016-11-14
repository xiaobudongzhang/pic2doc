import React from 'react';
import ReactDOM from 'react-dom';

var $ = require('jquery');
var Circle = require('./circle.js');
// var markdown = require('markdown').markdown;
const Markdown = require('./react/markdown-container.js');

function updateData() {
  $('a[data-toggle="tab"]').on('shown.bs.tab', function () {
    window.currentTabName = $(this).attr('href').match(/\w+/)[0];
  });
  $('#change-data').off('click').on('click', function () {
    $.ajax({
      url: '/data/content/update',
      type: 'POST',
      data: {
        map_page_sub_point_id: window.currentCicle.id,
        type_name: window.currentTabName || 'cp',
        send_dd: 1,
        content: window.currentTextareaValue
      },
      success: function (e) {
        console.log('update:', e);
      }
    });
  });
}

function loadCircle() {
  $('.position-circle').remove();
  window.currentCicle = {};
  $.ajax({
    url: '/data/point/getList',
    type: 'GET',
    data: {
      map_page_sub_id: map_page_sub_id
    },
    success: function (e) {
      var i;
      var x;
      var y;
      var circle;
      window.allCircle = e.data;
      function callback() {
        // 修改点内容
        updateData();
        const markdownDom = $('div[data-edit="markdown-container"]');
        markdownDom.map(function (index, item) {
          ReactDOM.render(<Markdown textareaValue={123} />, item);
          return true;
        });
      }
      for (i = 0; i < allCircle.length; i += 1) {
        x = allCircle[i].point_x;
        y = allCircle[i].point_y;
        circle = new Circle(x, y, {
          id: allCircle[i].id,
          callback: callback
        });
      }
    }
  });
}

function deleteCircle() {
  const btn = $('#delete-circle');
  btn.on('click', function () {
    const confirm = window.confirm('谨慎操作:\n确认要删除这个点吗?\n  1. 该信息点下的所有内容将被删除.\n  2. 可能无法恢复.');
    if (confirm) {
      window.currentCicle.deleteCircle(loadCircle);
    }
    return false;
  });
}

$(function() {

  // 加载点
  loadCircle();


  // 删除点
  deleteCircle();

  // 添加锚点事件
  $('body').on('click', function(e) {
    if (e.target.className == 'target-click') {
      var circle = new Circle(e.pageX, e.pageY);
      circle.storeCircle();
    }
  });
});
