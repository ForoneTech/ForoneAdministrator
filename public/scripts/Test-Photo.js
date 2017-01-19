/*
名称：图片浏览器
基于：jQuery v2.0.0+，Bootstrap v3.3.5+,jQuery Mousewheel 3.1.13+
作者：徐晓硕
版本：v1.0.0
时间：2015-11
说明：想显示那部分的图片，指定target即可 例：body .class #id
版本：v1.1.0
时间：2016-06
说明：优化了处理的算法，修改部分样式
*/

(function ($) {
    $.fn.Photos = function (options) {
        //全局photos数组
        var photos = [];
        //当前显示的图片index
        var showindex = 0;
        //当前是否可操作【当存在多个实例时,用于区分当前在操作的实例】
        var canOprate = false;

        var defaults = {
            target: "body",
            language: {
                zoom: "放大缩小",
                fullScreen: "切换全屏",
                download: "下载",
                close: "关闭",
                previous: "上一个",
                next: "下一个",
                clockwise: "顺时针旋转",
                anticlockwise: "逆时针旋转"
            }
        };
        var options = $.extend(defaults, options);

        if ($("#UIPhotos_ID").length <= 0) {
            $(document.body).append("<div class=\"UIPhotos\" id=\"UIPhotos_ID\">\
                                        <div class=\"UIPhotos-head\">\
                                            <div class=\"UIPhotos-counter\"></div>\
                                            <div class=\"UIPhotos-wise\">\
                                                <button id=\"UIPhotos-clockwise\" class=\"UIPhotos-buttonC\">\
                                                    <span class=\"glyphicon glyphicon-retweet\" aria-hidden=\"true\"></span>\
                                                </button>\
                                                <button id=\"UIPhotos-anticlockwise\" class=\"UIPhotos-buttonC UIPhotos-buttonC-anti\">\
                                                    <span class=\"glyphicon glyphicon-retweet\" aria-hidden=\"true\"></span>\
                                                </button>\
                                            </div>\
                                            <div class=\"UIPhotos-tool\">\
                                                <button id=\"UIPhotos-close\" class=\"UIPhotos-buttonR\">\
                                                    <span class=\"glyphicon glyphicon-remove\" aria-hidden=\"true\"></span>\
                                                </button>\
                                                <a id=\"UIPhotos-download\" class=\"UIPhotos-buttonR\" href=\"null\" target=\"_blank\" download=\"\">\
                                                    <span class=\"glyphicon glyphicon-save\" aria-hidden=\"true\"></span>\
                                                </a>\
                                                <button id=\"UIPhotos-fullScreen\" class=\"UIPhotos-buttonR\">\
                                                    <span class=\"glyphicon glyphicon-fullscreen\" aria-hidden=\"true\"></span>\
                                                </button>\
                                            </div>\
                                        </div>\
                                        <div class=\"UIPhotos-content\">\
                                            <button class=\"UIPhotos-buttonLeft\">\
                                                <span class=\"glyphicon glyphicon-chevron-left\" aria-hidden=\"true\"></span>\
                                            </button>\
                                            <img class=\"UIPhotos-img\" src=\"null\" />\
                                            <button class=\"UIPhotos-buttonRight\">\
                                                <span class=\"glyphicon glyphicon-chevron-right\" aria-hidden=\"true\"></span>\
                                            </button>\
                                        </div>\
                                        <div class=\"UIPhotos-foot\"></div>\
                                     </div>");
        }

        $("#UIPhotos-close").attr("title", options.language.close);
        $("#UIPhotos-download").attr("title", options.language.download);
        $("#UIPhotos-fullScreen").attr("title", options.language.fullScreen);
        $("#UIPhotos-clockwise").attr("title", options.language.clockwise);
        $("#UIPhotos-anticlockwise").attr("title", options.language.anticlockwise);
        $(".UIPhotos-buttonLeft").attr("title", options.language.previous);
        $(".UIPhotos-buttonRight").attr("title", options.language.next);

        //消除浏览器对图片拖拽打开新标签的方法
        function imgdragstart() { return false; }


        //当目标内img数量发生变化时要重新扫描图片
        $(options.target).bind('DOMNodeInserted', function () {
            photos = [];
            $(options.target).find("img[class!='UIPhotos-img']").each(function () {
                var img = {};
                img.src = $(this).attr("data-src") || $(this).attr("src");
                img.author = $(this).attr("data-author");
                img.figure = $(this).attr("data-figure");
                photos.push(img);
                $(this).attr("data-index", (photos.length - 1));
                $(this).css("cursor", "pointer");
                $(this).click(function () {
                    showindex = parseInt($(this).attr("data-index"));
                    showPhotos();
                    show();
                    canOprate = true;
                });
            });
        });
        $(options.target).bind('DOMNodeRemoved', function () {
            setTimeout(function () {
                photos = [];
                $(options.target).find("img[class!='UIPhotos-img']").each(function () {
                    var img = {};
                    img.src = $(this).attr("data-src") || $(this).attr("src");
                    img.author = $(this).attr("data-author");
                    img.figure = $(this).attr("data-figure");
                    photos.push(img);
                    console.log("img" + photos.length);
                    $(this).attr("data-index", (photos.length - 1));
                    $(this).css("cursor", "pointer");
                    $(this).click(function () {
                        showindex = parseInt($(this).attr("data-index"));
                        showPhotos();
                        show();
                        canOprate = true;
                    });
                });
            }, 20);

        });


        //扫描目标图片并绑定事件
        $(options.target).find("img[class!='UIPhotos-img']").each(function () {
            var img = {};
            img.src = $(this).attr("data-src") || $(this).attr("src");
            img.author = $(this).attr("data-author");
            img.figure = $(this).attr("data-figure");
            photos.push(img);
            $(this).attr("data-index", (photos.length - 1));
            $(this).css("cursor", "pointer");
            $(this).click(function () {
                $(document.body).css("overflow", "hidden");
                showindex = parseInt($(this).attr("data-index"));
                showPhotos();
                show();
                canOprate = true;
            });
        });

        //显示控件
        function showPhotos() {
            $("#UIPhotos_ID").css("display", "block");
        };

        //图片的宽高和位置
        var width;
        var height;
        var top;
        var left;
        //图片显示算法
        function show() {
            $(".UIPhotos-counter").html("" + (showindex + 1) + "/" + photos.length + "");

            var img = new Image();
            var imgWidth = 0;
            var imgHeight = 0;
            img.onload = function () {
                //原始宽度           
                imgWidth = this.width;
                //原始高度
                imgHeight = this.height;
                //记录原始图片高度和宽度
                photos[showindex].originalWidth = this.width.toString().replace("px", "");
                photos[showindex].originalHeight = this.height.toString().replace("px", "");

                $(".UIPhotos-img").attr({
                    "src": photos[showindex].src
                });
                //容器宽度
                var contentWidth = $(".UIPhotos-content").width();
                //容器高度
                var contentHeight = $(".UIPhotos-content").height();
                //当图片高度<容器高度 && 图片宽度<容器宽度  图片显示原始大小
                if (imgHeight <= contentHeight && imgWidth <= contentWidth) {
                    width = imgWidth;
                    height = imgHeight;
                }//当图片高度>容器高度 &&图片宽度<=容器宽度 图片高度为容器高度 等比例缩小
                else if (imgHeight > contentHeight && imgWidth <= contentWidth) {
                    height = contentHeight;
                    width = imgWidth / imgHeight * height;
                }//当图片高度<=容器高度 &&图片宽度>容器宽度 图片宽度为容器宽度 等比例缩小
                else if (imgHeight <= contentHeight && imgWidth > contentWidth) {
                    width = contentWidth;
                    height = imgHeight / imgWidth * width;
                }//当图片高度>容器高度 &&图片宽度>容器宽度
                else {
                    //首先图片高度为容器高度 等比例缩小
                    height = contentHeight;
                    width = imgWidth / imgHeight * height;
                    //如果图片宽度>容器宽度 图片宽度为容器宽度 等比例缩小
                    if (width > contentWidth) {
                        width = contentWidth;
                        height = imgHeight / imgWidth * width;
                    }
                }

                left = getLeft(width);
                top = 44 + getTop(height);

                $(".UIPhotos-img").css({
                    "top": top + "px",
                    "left": left + "px",
                    "height": height + "px",
                    "width": width + "px"
                });
                clearWise();
            }
            img.src = photos[showindex].src;

            $("#UIPhotos-download").attr("href", img.src);

        };
        function getLeft(width) {
            var contentWidth = $(".UIPhotos-content").width();
            return (contentWidth - width) / 2;
        };
        function getTop(height) {
            var contentHeight = $(".UIPhotos-content").height();
            return (contentHeight - height) / 2;
        };

        //放大缩小算法
        $(".UIPhotos-content").mousewheel(function (event, delta) {
            if (canOprate) {
                var oldTop = $(".UIPhotos-img").css("top").replace("px", "");
                var oldLeft = $(".UIPhotos-img").css("left").replace("px", "");
                var oldHeight = $(".UIPhotos-img").css("height").replace("px", "");
                var oldWidth = $(".UIPhotos-img").css("width").replace("px", "");
                //当图片太大或太小时 不再进行放大缩小处理
                if ((oldHeight < 34 && delta < 0)
                    || (oldWidth < 34 && delta < 0)) { }
                else if ((photos[showindex].originalWidth <= 1700 && photos[showindex].originalHeight <= 1700 && oldWidth > 3400 && delta > 0)
                    || (photos[showindex].originalWidth <= 1700 && photos[showindex].originalHeight <= 1700 && oldHeight > 3400 && delta > 0)) { }
                else if ((photos[showindex].originalWidth > 1700 || photos[showindex].originalHeight > 1700)
                    && (oldWidth > 2 * photos[showindex].originalWidth
                    || oldHeight > 2 * photos[showindex].originalHeight) && delta > 0) { }
                else {
                    var newHeight = parseFloat(oldHeight) * (1 + parseFloat(delta) / 20);
                    var newWidth = parseFloat(oldWidth) * (1 + parseFloat(delta) / 20);

                    var newTop;
                    var newLeft;
                    //缩小图片时计算边界距离 防止图片跑到容器外面
                    if (delta < 0) {
                        //容器宽度
                        var contentWidth = $(".UIPhotos-content").width();
                        //容器高度
                        var contentHeight = $(".UIPhotos-content").height();
                        //右上角判断
                        if ((contentWidth - parseFloat(oldLeft) <= 17) && (parseFloat(oldTop) + newHeight <= 17 + 44)) {
                            newTop = -newHeight + 44 + 17;
                            newLeft = contentWidth - 17;
                        }//左上角判断
                        else if ((parseFloat(oldLeft) + newWidth <= 17) && (parseFloat(oldTop) + newHeight <= 17 + 44)) {
                            newTop = -newHeight + 44 + 17;
                            newLeft = -newWidth + 17;
                        }//左下角判断
                        else if ((parseFloat(oldLeft) + newWidth <= 17) && (contentHeight - parseFloat(oldTop) - 44 <= 17)) {
                            newTop = contentHeight - 44 - 17;
                            newLeft = -newWidth + 17;
                        }//右下角判断
                        else if ((contentWidth - parseFloat(oldLeft) <= 17) && (contentHeight - parseFloat(oldTop) - 44 <= 17)) {
                            newTop = contentHeight - 44 - 17;
                            newLeft = contentWidth - 17;
                        }//右边判断
                        else if (contentWidth - parseFloat(oldLeft) <= 17) {
                            newTop = parseFloat(oldTop) - (newHeight - parseFloat(oldHeight)) / 2;
                            newLeft = contentWidth - 17;
                        }//左边判断
                        else if (parseFloat(oldLeft) + newWidth <= 17) {
                            newTop = parseFloat(oldTop) - (newHeight - parseFloat(oldHeight)) / 2;
                            newLeft = -newWidth + 17;
                        }//上边判断
                        else if (parseFloat(oldTop) + newHeight <= 17 + 44) {
                            newTop = -newHeight + 44 + 17;
                            newLeft = parseFloat(oldLeft) - (newWidth - parseFloat(oldWidth)) / 2;
                        }//下边判断
                        else if (contentHeight - parseFloat(oldTop) - 44 <= 17) {
                            newTop = contentHeight - 44 - 17;
                            newLeft = parseFloat(oldLeft) - (newWidth - parseFloat(oldWidth)) / 2;
                        }
                        else {
                            newTop = parseFloat(oldTop) - (newHeight - parseFloat(oldHeight)) / 2;
                            newLeft = parseFloat(oldLeft) - (newWidth - parseFloat(oldWidth)) / 2;
                        }

                    } else {
                        newTop = parseFloat(oldTop) - (newHeight - parseFloat(oldHeight)) / 2;
                        newLeft = parseFloat(oldLeft) - (newWidth - parseFloat(oldWidth)) / 2;
                    }
                    $(".UIPhotos-img").css({
                        "top": newTop + "px",
                        "left": newLeft + "px",
                        "height": newHeight + "px",
                        "width": newWidth + "px"
                    });
                }
            }
        });

        //窗口调整
        $(window).resize(function () {
            if (canOprate) {
                show();
            }
        });

        //旋转参数
        var rotateK = 0;
        $("#UIPhotos-clockwise").click(function () {
            $(".UIPhotos-img").css({
                "transform": "rotate(" + (90 * rotateK + 90) + "deg)",
                "-ms-transform": "rotate(" + (90 * rotateK + 90) + "deg)",
                "-webkit-transform": "rotate(" + (90 * rotateK + 90) + "deg)"
            });
            rotateK++;
        });
        $("#UIPhotos-anticlockwise").click(function () {
            $(".UIPhotos-img").css({
                "transform": "rotate(" + (90 * rotateK - 90) + "deg)",
                "-ms-transform": "rotate(" + (90 * rotateK - 90) + "deg)",
                "-webkit-transform": "rotate(" + (90 * rotateK - 90) + "deg)"
            });
            rotateK--;
        });

        function clearWise() {
            rotateK = 0;
            $(".UIPhotos-img").css({
                "transform": "rotate(0deg)",
                "-ms-transform": "rotate(0deg)",
                "-webkit-transform": "rotate(0deg)"
            });
        }


        //切换
        $(".UIPhotos-buttonLeft").click(function () {
            if (canOprate) {
                if (showindex > 0) {
                    showindex = showindex - 1;
                }
                else {
                    showindex = photos.length - 1;
                }
                show();
            }
        });
        $(".UIPhotos-buttonRight").click(function () {
            if (canOprate) {
                if (showindex < photos.length - 1) {
                    showindex = showindex + 1;
                }
                else {
                    showindex = 0;
                }
                show();
            }
        });

        var by;
        var mxy;

        //拖动
        $(".UIPhotos-img").bind({
            mousedown: function (event) {

                $(".UIPhotos-content").addClass("UIPhotos-content-grabbing");


                mxy = UImousepoint(event);//获取当前鼠标坐标

                by = { x: mxy.x - parseFloat($(this).css("left").replace("px", "")), y: mxy.y - parseFloat($(this).css("top").replace("px", "")) };
                document.addEventListener("mousemove", UImove_mousemove, false);
                document.addEventListener("mouseup", UImove_mouseup, false);

            }
        });
        $(".UIPhotos-content").bind({
            mouseup: function () {

                $(".UIPhotos-content").removeClass("UIPhotos-content-grabbing")
            }
        });
        function UImousepoint(e) {
            //获取鼠标坐标 请传递evnet参数
            e = e || window.event;
            var m = (e.pageX || e.pageY) ? { x: e.pageX, y: e.pageY } : { x: e.clientX + document.body.scrollLeft - document.body.clientLeft, y: e.clientY + document.body.scrollTop - document.body.clientTop };
            return m;
        };
        function UImove_mousemove(ev) {
            var mxy1 = UImousepoint(ev);
            var contentWidth = parseFloat($(".UIPhotos-content").width());
            var contentHeight = parseFloat($(".UIPhotos-content").height());

            var imgHeight = parseFloat($(".UIPhotos-img").css("height").replace("px", ""));
            var imgWidth = parseFloat($(".UIPhotos-img").css("width").replace("px", ""));
            if ((mxy1.x - by.x) < (-imgWidth + 34)
                || (mxy1.x - by.x) > (contentWidth - 34)
                || (mxy1.y - by.y) < (-imgHeight + 34 + 44)
                || (mxy1.y - by.y) > (contentHeight - 34)
               ) { }
            else
            {
                $(".UIPhotos-img").css("left", "" + (mxy1.x - by.x) + "px");
                $(".UIPhotos-img").css("top", "" + (mxy1.y - by.y) + "px");
            }
        };
        function UImove_mouseup() {
            document.removeEventListener("mousemove", UImove_mousemove, false);
        };


        //全屏显示
        function launchFullScreen(element) {
            if (element.requestFullScreen) {
                element.requestFullScreen();
            } else if (element.mozRequestFullScreen) {
                element.mozRequestFullScreen();
            } else if (element.webkitRequestFullScreen) {
                element.webkitRequestFullScreen();
            } else if (element.msRequestFullscreen) {
                element.msRequestFullscreen();
            }

        }
        //退出全屏显示
        function exitFullScreen() {
            if (document.exitFullscreen) {
                document.exitFullscreen();
            } else if (document.mozCancelFullScreen) {
                document.mozCancelFullScreen();
            } else if (document.webkitExitFullscreen) {
                document.webkitExitFullscreen();
            } else if (document.msExitFullscreen) {
                document.msExitFullscreen();
            }
        }
        //判断当前是否全屏
        function isFullscreen() {
            var fullscreenEnabled =
                                    document.fullscreenElement ||
                                    document.mozFullScreenElement ||
                                    document.webkitFullscreenElement ||
                                    document.msFullscreenElement;
            return fullscreenEnabled;
        }
        //全屏切换
        $("#UIPhotos-fullScreen").click(function () {
            if (canOprate) {

                if (isFullscreen()) {

                    exitFullScreen();
                }
                else {

                    launchFullScreen(document.getElementById("UIPhotos_ID"));
                }
            }
        });
        //关闭窗口
        $("#UIPhotos-close").click(function () {
            $(document.body).css("overflow", "auto");
            canOprate = false;
            exitFullScreen();
            $("#UIPhotos_ID").css("display", "none");
            $(document.body).css("overflow", "auto");
        });

        //消除浏览器对图片拖拽打开新标签的方法
        for (i in document.images) document.images[i].ondragstart = imgdragstart;
    }
})(jQuery)