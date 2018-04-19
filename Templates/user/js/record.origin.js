(function () {
    //引入命名空间
    var GLOBAL = {};

    GLOBAL.namespace = function (str) {
        var arr = str.split('.'), o = GLOBAL;
        for (k = (arr[0] == "GLOBAL") ? 1 : 0; k < arr.length; k++) {
            o[arr[k]] = o[arr[k]] || {};
            o = o[arr[k]];
        }
    };

    GLOBAL.namespace("Util");

    GLOBAL.Util.getDomain = function(){
        return location.href.match(/(?:jinfuzi|jrgang|xueqiu360|jfz)\.\w+/);
    };
    GLOBAL.domain=GLOBAL.Util.getDomain();
    /*Fingerprint*/
    GLOBAL.Util.Fingerprint = function(){
        var Fingerprint = function (options) {
            var nativeForEach, nativeMap;
            nativeForEach = Array.prototype.forEach;
            nativeMap = Array.prototype.map;

            this.each = function (obj, iterator, context) {
                if (obj === null) {
                    return;
                }
                if (nativeForEach && obj.forEach === nativeForEach) {
                    obj.forEach(iterator, context);
                } else if (obj.length === +obj.length) {
                    for (var i = 0, l = obj.length; i < l; i++) {
                        if (iterator.call(context, obj[i], i, obj) === {}) return;
                    }
                } else {
                    for (var key in obj) {
                        if (obj.hasOwnProperty(key)) {
                            if (iterator.call(context, obj[key], key, obj) === {}) return;
                        }
                    }
                }
            };

            this.map = function(obj, iterator, context) {
                var results = [];
                // Not using strict equality so that this acts as a
                // shortcut to checking for `null` and `undefined`.
                if (obj == null) return results;
                if (nativeMap && obj.map === nativeMap) return obj.map(iterator, context);
                this.each(obj, function(value, index, list) {
                    results[results.length] = iterator.call(context, value, index, list);
                });
                return results;
            };

            if (typeof options == 'object'){
                this.hasher = options.hasher;
                this.screen_resolution = options.screen_resolution;
                this.canvas = options.canvas;
                this.ie_activex = options.ie_activex;
            } else if(typeof options == 'function'){
                this.hasher = options;
            }
        };

        Fingerprint.prototype = {
            get: function(){
                var keys = [],
                    now = new Date();
                keys.push(navigator.userAgent);
                keys.push(navigator.language);
                keys.push(screen.colorDepth);
                if (this.screen_resolution) {
                    var resolution = this.getScreenResolution();
                    if (typeof resolution !== 'undefined'){ // headless browsers, such as phantomjs
                        keys.push(this.getScreenResolution().join('x'));
                    }
                }
                keys.push(new Date().getTimezoneOffset());
                keys.push(this.hasSessionStorage());
                keys.push(this.hasLocalStorage());
                keys.push(!!window.indexedDB);
                //body might not be defined at this point or removed programmatically
                if(document.body){
                    keys.push(typeof(document.body.addBehavior));
                } else {
                    keys.push(typeof undefined);
                }
                keys.push(typeof(window.openDatabase));
                keys.push(navigator.cpuClass);
                keys.push(navigator.platform);
                keys.push(navigator.doNotTrack);
                keys.push(this.getPluginsString());
                if(this.canvas && this.isCanvasSupported()){
                    keys.push(this.getCanvasFingerprint());
                }
                /*加多个访问时间*/
                keys.push(now.getTime());
                if(this.hasher){
                    return this.hasher(keys.join('###'), 31);
                } else {
                    return this.murmurhash3_32_gc(keys.join('###'), 31);
                }
            },

            /**
             * JS Implementation of MurmurHash3 (r136) (as of May 20, 2011)
             *
             * @author <a href="mailto:gary.court@gmail.com">Gary Court</a>
             * @see http://github.com/garycourt/murmurhash-js
             * @author <a href="mailto:aappleby@gmail.com">Austin Appleby</a>
             * @see http://sites.google.com/site/murmurhash/
             *
             * @param {string} key ASCII only
             * @param {number} seed Positive integer only
             * @return {number} 32-bit positive integer hash
             */

            murmurhash3_32_gc: function(key, seed) {
                var remainder, bytes, h1, h1b, c1, c2, k1, i;

                remainder = key.length & 3; // key.length % 4
                bytes = key.length - remainder;
                h1 = seed;
                c1 = 0xcc9e2d51;
                c2 = 0x1b873593;
                i = 0;

                while (i < bytes) {
                    k1 =
                        ((key.charCodeAt(i) & 0xff)) |
                        ((key.charCodeAt(++i) & 0xff) << 8) |
                        ((key.charCodeAt(++i) & 0xff) << 16) |
                        ((key.charCodeAt(++i) & 0xff) << 24);
                    ++i;

                    k1 = ((((k1 & 0xffff) * c1) + ((((k1 >>> 16) * c1) & 0xffff) << 16))) & 0xffffffff;
                    k1 = (k1 << 15) | (k1 >>> 17);
                    k1 = ((((k1 & 0xffff) * c2) + ((((k1 >>> 16) * c2) & 0xffff) << 16))) & 0xffffffff;

                    h1 ^= k1;
                    h1 = (h1 << 13) | (h1 >>> 19);
                    h1b = ((((h1 & 0xffff) * 5) + ((((h1 >>> 16) * 5) & 0xffff) << 16))) & 0xffffffff;
                    h1 = (((h1b & 0xffff) + 0x6b64) + ((((h1b >>> 16) + 0xe654) & 0xffff) << 16));
                }

                k1 = 0;

                switch (remainder) {
                    case 3: k1 ^= (key.charCodeAt(i + 2) & 0xff) << 16;
                    case 2: k1 ^= (key.charCodeAt(i + 1) & 0xff) << 8;
                    case 1: k1 ^= (key.charCodeAt(i) & 0xff);

                        k1 = (((k1 & 0xffff) * c1) + ((((k1 >>> 16) * c1) & 0xffff) << 16)) & 0xffffffff;
                        k1 = (k1 << 15) | (k1 >>> 17);
                        k1 = (((k1 & 0xffff) * c2) + ((((k1 >>> 16) * c2) & 0xffff) << 16)) & 0xffffffff;
                        h1 ^= k1;
                }

                h1 ^= key.length;

                h1 ^= h1 >>> 16;
                h1 = (((h1 & 0xffff) * 0x85ebca6b) + ((((h1 >>> 16) * 0x85ebca6b) & 0xffff) << 16)) & 0xffffffff;
                h1 ^= h1 >>> 13;
                h1 = ((((h1 & 0xffff) * 0xc2b2ae35) + ((((h1 >>> 16) * 0xc2b2ae35) & 0xffff) << 16))) & 0xffffffff;
                h1 ^= h1 >>> 16;

                return h1 >>> 0;
            },

            // https://bugzilla.mozilla.org/show_bug.cgi?id=781447
            hasLocalStorage: function () {
                try{
                    return !!window.localStorage;
                } catch(e) {
                    return true; // SecurityError when referencing it means it exists
                }
            },

            hasSessionStorage: function () {
                try{
                    return !!window.sessionStorage;
                } catch(e) {
                    return true; // SecurityError when referencing it means it exists
                }
            },

            isCanvasSupported: function () {
                var elem = document.createElement('canvas');
                return !!(elem.getContext && elem.getContext('2d'));
            },

            isIE: function () {
                if(navigator.appName === 'Microsoft Internet Explorer') {
                    return true;
                } else if(navigator.appName === 'Netscape' && /Trident/.test(navigator.userAgent)){// IE 11
                    return true;
                }
                return false;
            },

            getPluginsString: function () {
                if(this.isIE() && this.ie_activex){
                    return this.getIEPluginsString();
                } else {
                    return this.getRegularPluginsString();
                }
            },

            getRegularPluginsString: function () {
                return this.map(navigator.plugins, function (p) {
                    var mimeTypes = this.map(p, function(mt){
                        return [mt.type, mt.suffixes].join('~');
                    }).join(',');
                    return [p.name, p.description, mimeTypes].join('::');
                }, this).join(';');
            },

            getIEPluginsString: function () {
                if(window.ActiveXObject){
                    var names = ['ShockwaveFlash.ShockwaveFlash',//flash plugin
                        'AcroPDF.PDF', // Adobe PDF reader 7+
                        'PDF.PdfCtrl', // Adobe PDF reader 6 and earlier, brrr
                        'QuickTime.QuickTime', // QuickTime
                        // 5 versions of real players
                        'rmocx.RealPlayer G2 Control',
                        'rmocx.RealPlayer G2 Control.1',
                        'RealPlayer.RealPlayer(tm) ActiveX Control (32-bit)',
                        'RealVideo.RealVideo(tm) ActiveX Control (32-bit)',
                        'RealPlayer',
                        'SWCtl.SWCtl', // ShockWave player
                        'WMPlayer.OCX', // Windows media player
                        'AgControl.AgControl', // Silverlight
                        'Skype.Detection'];

                    // starting to detect plugins in IE
                    return this.map(names, function(name){
                        try{
                            new ActiveXObject(name);
                            return name;
                        } catch(e){
                            return null;
                        }
                    }).join(';');
                } else {
                    return ""; // behavior prior version 0.5.0, not breaking backwards compat.
                }
            },

            getScreenResolution: function () {
                return [screen.height, screen.width];
            },

            getCanvasFingerprint: function () {
                var canvas = document.createElement('canvas');
                var ctx = canvas.getContext('2d');
                // https://www.browserleaks.com/canvas#how-does-it-work
                var txt = 'http://valve.github.io';
                ctx.textBaseline = "top";
                ctx.font = "14px 'Arial'";
                ctx.textBaseline = "alphabetic";
                ctx.fillStyle = "#f60";
                ctx.fillRect(125,1,62,20);
                ctx.fillStyle = "#069";
                ctx.fillText(txt, 2, 15);
                ctx.fillStyle = "rgba(102, 204, 0, 0.7)";
                ctx.fillText(txt, 4, 17);
                return canvas.toDataURL();
            }
        };

        return Fingerprint;
    };

    GLOBAL.Util.trim = function (str) {
        return str.replace(/(^\s*)|(\s*$)/g, "");
    };
    //cookie
    GLOBAL.Util.cookie = function (name, value, options) {
        if (typeof value != 'undefined') { // name and value given, set cookie
            options = options || {};
            if (value === null) {
                value = '';
                options.expires = -1;
            }
            var expires = '';
            if (options.expires && (typeof options.expires == 'number' || options.expires.toUTCString)) {
                var date;
                if (typeof options.expires == 'number') {
                    date = new Date();
                    date.setTime(date.getTime() + (options.expires * 24 * 60 * 60 * 1000));
                } else {
                    date = options.expires;
                }
                expires = '; expires=' + date.toUTCString(); // use expires attribute, max-age is not supported by IE
            }
            var path = options.path ? '; path=' + options.path : '';
            var domain = options.domain ? '; domain=' + options.domain : '';
            var secure = options.secure ? '; secure' : '';
            document.cookie = [name, '=', encodeURIComponent(value), expires, path, domain, secure].join('');
        } else { // only name given, get cookie
            var cookieValue = '';
            if (document.cookie && document.cookie != '') {
                var cookies = document.cookie.split(';');
                for (var i = 0; i < cookies.length; i++) {
                    var cookie = GLOBAL.Util.trim(cookies[i]);
                    // Does this cookie string begin with the name we want?
                    if (cookie.substring(0, name.length + 1) == (name + '=')) {
                        cookieValue = decodeURIComponent(cookie.substring(name.length + 1));
                        break;
                    }
                }
            }
            return cookieValue;
        }
    };

    //ajax
    var createAjax = function() {
        var xhr = null;
        try {
            //IE系列浏览器
            xhr = new ActiveXObject("microsoft.xmlhttp");
        } catch (e1) {
            try {
                //非IE浏览器
                xhr = new XMLHttpRequest();
            } catch (e2) {
                alert("您的浏览器不支持ajax，请更换！");
            }
        }
        return xhr;
    };
    GLOBAL.Util.ajax = function(conf) {
        // 初始化
        //type参数,可选
        var type = conf.type;
        //url参数，必填
        var url = conf.url;
        //data参数可选，只有在post请求时需要
        var data = conf.data;
        //datatype参数可选
        var dataType = conf.dataType;
        //回调函数可选
        var success = conf.success;

        if (type == null){
            //type参数可选，默认为get
            type = "get";
        }
        if (dataType == null){
            //dataType参数可选，默认为text
            dataType = "text";
        }
        // 创建ajax引擎对象
        var xhr = createAjax();
        // 打开
        xhr.open(type, url, true);
        // 发送
        if (type == "GET" || type == "get") {
            xhr.send(null);
        } else if (type == "POST" || type == "post") {
            xhr.setRequestHeader("content-type",
                "application/x-www-form-urlencoded");
            xhr.send(data);
        }
        xhr.onreadystatechange = function() {
            if (xhr.readyState == 4 && xhr.status == 200) {
                if(dataType == "text"||dataType=="TEXT") {
                    if (success != null){
                        //普通文本
                        success(xhr.responseText);
                    }
                }else if(dataType=="xml"||dataType=="XML") {
                    if (success != null){
                        //接收xml文档
                        success(xhr.responseXML);
                    }
                }else if(dataType=="json"||dataType=="JSON") {
                    if (success != null){
                        //将json字符串转换为js对象
                        success(eval("("+xhr.responseText+")"));
                    }
                }
            }
        };
    };
    /*md5加密算法*/
    function safe_add(x, y) {
        var lsw = (x & 0xFFFF) + (y & 0xFFFF),
            msw = (x >> 16) + (y >> 16) + (lsw >> 16);
        return (msw << 16) | (lsw & 0xFFFF);
    }

    function bit_rol(num, cnt) {
        return (num << cnt) | (num >>> (32 - cnt));
    }

    function md5_cmn(q, a, b, x, s, t) {
        return safe_add(bit_rol(safe_add(safe_add(a, q), safe_add(x, t)), s), b);
    }

    function md5_ff(a, b, c, d, x, s, t) {
        return md5_cmn((b & c) | ((~b) & d), a, b, x, s, t);
    }

    function md5_gg(a, b, c, d, x, s, t) {
        return md5_cmn((b & d) | (c & (~d)), a, b, x, s, t);
    }

    function md5_hh(a, b, c, d, x, s, t) {
        return md5_cmn(b ^ c ^ d, a, b, x, s, t);
    }

    function md5_ii(a, b, c, d, x, s, t) {
        return md5_cmn(c ^ (b | (~d)), a, b, x, s, t);
    }

    function binl_md5(x, len) {
        /* append padding */
        x[len >> 5] |= 0x80 << (len % 32);
        x[(((len + 64) >>> 9) << 4) + 14] = len;

        var i, olda, oldb, oldc, oldd,
            a = 1732584193,
            b = -271733879,
            c = -1732584194,
            d = 271733878;

        for (i = 0; i < x.length; i += 16) {
            olda = a;
            oldb = b;
            oldc = c;
            oldd = d;

            a = md5_ff(a, b, c, d, x[i], 7, -680876936);
            d = md5_ff(d, a, b, c, x[i + 1], 12, -389564586);
            c = md5_ff(c, d, a, b, x[i + 2], 17, 606105819);
            b = md5_ff(b, c, d, a, x[i + 3], 22, -1044525330);
            a = md5_ff(a, b, c, d, x[i + 4], 7, -176418897);
            d = md5_ff(d, a, b, c, x[i + 5], 12, 1200080426);
            c = md5_ff(c, d, a, b, x[i + 6], 17, -1473231341);
            b = md5_ff(b, c, d, a, x[i + 7], 22, -45705983);
            a = md5_ff(a, b, c, d, x[i + 8], 7, 1770035416);
            d = md5_ff(d, a, b, c, x[i + 9], 12, -1958414417);
            c = md5_ff(c, d, a, b, x[i + 10], 17, -42063);
            b = md5_ff(b, c, d, a, x[i + 11], 22, -1990404162);
            a = md5_ff(a, b, c, d, x[i + 12], 7, 1804603682);
            d = md5_ff(d, a, b, c, x[i + 13], 12, -40341101);
            c = md5_ff(c, d, a, b, x[i + 14], 17, -1502002290);
            b = md5_ff(b, c, d, a, x[i + 15], 22, 1236535329);

            a = md5_gg(a, b, c, d, x[i + 1], 5, -165796510);
            d = md5_gg(d, a, b, c, x[i + 6], 9, -1069501632);
            c = md5_gg(c, d, a, b, x[i + 11], 14, 643717713);
            b = md5_gg(b, c, d, a, x[i], 20, -373897302);
            a = md5_gg(a, b, c, d, x[i + 5], 5, -701558691);
            d = md5_gg(d, a, b, c, x[i + 10], 9, 38016083);
            c = md5_gg(c, d, a, b, x[i + 15], 14, -660478335);
            b = md5_gg(b, c, d, a, x[i + 4], 20, -405537848);
            a = md5_gg(a, b, c, d, x[i + 9], 5, 568446438);
            d = md5_gg(d, a, b, c, x[i + 14], 9, -1019803690);
            c = md5_gg(c, d, a, b, x[i + 3], 14, -187363961);
            b = md5_gg(b, c, d, a, x[i + 8], 20, 1163531501);
            a = md5_gg(a, b, c, d, x[i + 13], 5, -1444681467);
            d = md5_gg(d, a, b, c, x[i + 2], 9, -51403784);
            c = md5_gg(c, d, a, b, x[i + 7], 14, 1735328473);
            b = md5_gg(b, c, d, a, x[i + 12], 20, -1926607734);

            a = md5_hh(a, b, c, d, x[i + 5], 4, -378558);
            d = md5_hh(d, a, b, c, x[i + 8], 11, -2022574463);
            c = md5_hh(c, d, a, b, x[i + 11], 16, 1839030562);
            b = md5_hh(b, c, d, a, x[i + 14], 23, -35309556);
            a = md5_hh(a, b, c, d, x[i + 1], 4, -1530992060);
            d = md5_hh(d, a, b, c, x[i + 4], 11, 1272893353);
            c = md5_hh(c, d, a, b, x[i + 7], 16, -155497632);
            b = md5_hh(b, c, d, a, x[i + 10], 23, -1094730640);
            a = md5_hh(a, b, c, d, x[i + 13], 4, 681279174);
            d = md5_hh(d, a, b, c, x[i], 11, -358537222);
            c = md5_hh(c, d, a, b, x[i + 3], 16, -722521979);
            b = md5_hh(b, c, d, a, x[i + 6], 23, 76029189);
            a = md5_hh(a, b, c, d, x[i + 9], 4, -640364487);
            d = md5_hh(d, a, b, c, x[i + 12], 11, -421815835);
            c = md5_hh(c, d, a, b, x[i + 15], 16, 530742520);
            b = md5_hh(b, c, d, a, x[i + 2], 23, -995338651);

            a = md5_ii(a, b, c, d, x[i], 6, -198630844);
            d = md5_ii(d, a, b, c, x[i + 7], 10, 1126891415);
            c = md5_ii(c, d, a, b, x[i + 14], 15, -1416354905);
            b = md5_ii(b, c, d, a, x[i + 5], 21, -57434055);
            a = md5_ii(a, b, c, d, x[i + 12], 6, 1700485571);
            d = md5_ii(d, a, b, c, x[i + 3], 10, -1894986606);
            c = md5_ii(c, d, a, b, x[i + 10], 15, -1051523);
            b = md5_ii(b, c, d, a, x[i + 1], 21, -2054922799);
            a = md5_ii(a, b, c, d, x[i + 8], 6, 1873313359);
            d = md5_ii(d, a, b, c, x[i + 15], 10, -30611744);
            c = md5_ii(c, d, a, b, x[i + 6], 15, -1560198380);
            b = md5_ii(b, c, d, a, x[i + 13], 21, 1309151649);
            a = md5_ii(a, b, c, d, x[i + 4], 6, -145523070);
            d = md5_ii(d, a, b, c, x[i + 11], 10, -1120210379);
            c = md5_ii(c, d, a, b, x[i + 2], 15, 718787259);
            b = md5_ii(b, c, d, a, x[i + 9], 21, -343485551);

            a = safe_add(a, olda);
            b = safe_add(b, oldb);
            c = safe_add(c, oldc);
            d = safe_add(d, oldd);
        }
        return [a, b, c, d];
    }

    function binl2rstr(input) {
        var i,
            output = '';
        for (i = 0; i < input.length * 32; i += 8) {
            output += String.fromCharCode((input[i >> 5] >>> (i % 32)) & 0xFF);
        }
        return output;
    }

    function rstr2binl(input) {
        var i,
            output = [];
        output[(input.length >> 2) - 1] = undefined;
        for (i = 0; i < output.length; i += 1) {
            output[i] = 0;
        }
        for (i = 0; i < input.length * 8; i += 8) {
            output[i >> 5] |= (input.charCodeAt(i / 8) & 0xFF) << (i % 32);
        }
        return output;
    }

    function rstr_md5(s) {
        return binl2rstr(binl_md5(rstr2binl(s), s.length * 8));
    }

    function rstr_hmac_md5(key, data) {
        var i,
            bkey = rstr2binl(key),
            ipad = [],
            opad = [],
            hash;
        ipad[15] = opad[15] = undefined;
        if (bkey.length > 16) {
            bkey = binl_md5(bkey, key.length * 8);
        }
        for (i = 0; i < 16; i += 1) {
            ipad[i] = bkey[i] ^ 0x36363636;
            opad[i] = bkey[i] ^ 0x5C5C5C5C;
        }
        hash = binl_md5(ipad.concat(rstr2binl(data)), 512 + data.length * 8);
        return binl2rstr(binl_md5(opad.concat(hash), 512 + 128));
    }

    function rstr2hex(input) {
        var hex_tab = '0123456789abcdef',
            output = '',
            x,
            i;
        for (i = 0; i < input.length; i += 1) {
            x = input.charCodeAt(i);
            output += hex_tab.charAt((x >>> 4) & 0x0F) +
            hex_tab.charAt(x & 0x0F);
        }
        return output;
    }

    function str2rstr_utf8(input) {
        return unescape(encodeURIComponent(input));
    }

    function raw_md5(s) {
        return rstr_md5(str2rstr_utf8(s));
    }

    function hex_md5(s) {
        return rstr2hex(raw_md5(s));
    }

    function raw_hmac_md5(k, d) {
        return rstr_hmac_md5(str2rstr_utf8(k), str2rstr_utf8(d));
    }

    function hex_hmac_md5(k, d) {
        return rstr2hex(raw_hmac_md5(k, d));
    }

    function md5(string, key, raw) {
        if (!key) {
            if (!raw) {
                return hex_md5(string);
            }
            return raw_md5(string);
        }
        if (!raw) {
            return hex_hmac_md5(key, string);
        }
        return raw_hmac_md5(key, string);
    }

    GLOBAL.Util.md5 = md5;

    //生成识别用户唯一的ID
    function createUserID() {
        var randomStr = (new Date()) + getuserAgent() + getUserUrl() + Math.random() * 10000;
        return md5(randomStr);
    }

    var retry_id_times = 10;
    //获取用户识别ID
    function getUserID() {
        if (GLOBAL.Util.cookie("JRECORD_UID") != null && GLOBAL.Util.cookie("JRECORD_UID").length > 0) {
            return GLOBAL.Util.cookie("JRECORD_UID");
        } else {
            var Finger = GLOBAL.Util.Fingerprint({ie_activex: true});
            var finger = new Finger();
            var fingerStr = md5(finger.get());
            if(fingerStr.length == 0){
                if(retry_id_times != 0){
                    fingerStr = getUserID();
                    --retry_id_times;
                }else{
                    fingerStr = 'i_can_not_get_fingerprint';
                }
            }
            GLOBAL.Util.cookie("JRECORD_UID", fingerStr, {
                expires: 10000,
                path: '/',
                domain: GLOBAL.domain
            });
            return fingerStr;
        }
    }

    //获取用户的refer
    function getUserRefer() {
        return document.referrer;
    }

    //获取src
    function getUserSrc()
    {
        var refer = getUserRefer();
        var reg = /^(?:http|https):\/\/\w*\.(?:jinfuzi|jrgang|xueqiu360|jfz)\./;
        var validRefer = !reg.test(refer) && refer.length > 0;
        if (!validRefer) {
            return GLOBAL.Util.cookie("JRECORD_SRC");
        } else {
            GLOBAL.Util.cookie("JRECORD_SRC", refer, {
                expires: 10000,
                path: '/',
                domain: GLOBAL.domain
            });
        }
        return refer;
    }

    //获取land page
    function getUserLandPage()
    {
        var refer = getUserRefer(),
            reg = /^(?:http|https):\/\/\w*\.(?:jinfuzi|jrgang|xueqiu360|jfz)\./,
            validRefer = !reg.test(refer);
        if(validRefer)
        {
            GLOBAL.Util.cookie("JRECORD_LANDPAGE", getUserUrl(), {
                expires: 10000,
                path: '/',
                domain: GLOBAL.domain
            });
        }
        return GLOBAL.Util.cookie("JRECORD_LANDPAGE")!=null ? GLOBAL.Util.cookie("JRECORD_LANDPAGE") : null;
    }

    //获取sign
    function getSign()
    {
        var sign = get('sign');
        if(sign!=0&&sign!= GLOBAL.Util.cookie('JRECORD_SIGN'))
        {
            GLOBAL.Util.cookie('JRECORD_SIGN',
                sign,
                {
                    domain:GLOBAL.domain,
                    path:'/'
                }
            );
        }
        return GLOBAL.Util.cookie('JRECORD_SIGN') == null ? '' : GLOBAL.Util.cookie('JRECORD_SIGN');
    }

    //获取first_time
    function getFirstTime()
    {
        var cru_time = getCtime();
        if(GLOBAL.Util.cookie('JRECORD_FTIME') == '')
        {
            GLOBAL.Util.cookie('JRECORD_FTIME',
                cru_time,
                {
                    expires:10000,
                    domain:GLOBAL.domain,
                    path:'/'
                }
            );
        }
        return GLOBAL.Util.cookie('JRECORD_FTIME');
    }

    //获取last_time
    function getLastTime()
    {
        var now = new Date(),
            cru_time = Math.round(now.getTime() / 1000);
        if(GLOBAL.Util.cookie('JRECORD_CTIME') != '')
        {
            var times = cru_time - GLOBAL.Util.cookie('JRECORD_CTIME');
            if(times >1800)
            {
                GLOBAL.Util.cookie('JRECORD_LTIME',
                    GLOBAL.Util.cookie('JRECORD_CTIME'),
                    {
                        expires:10000,
                        domain:GLOBAL.domain,
                        path:'/'
                    }
                );
            }
        }else{
            GLOBAL.Util.cookie('JRECORD_LTIME',
                GLOBAL.Util.cookie('JRECORD_FTIME'),
                {
                    expires:10000,
                    domain:GLOBAL.domain,
                    path:'/'
                }
            );
        }
        GLOBAL.Util.cookie('JRECORD_CTIME',
            cru_time,
            {
                expires:10000,
                domain:GLOBAL.domain,
                path:'/'
            }
        );
        return GLOBAL.Util.cookie('JRECORD_LTIME');
    }

    //获取用户当前代理的URL
    function getUserUrl() {
        return window.location.href;
    }

    //获取用户代理的agent
    function getUserAgent() {
        return navigator.userAgent;
    }

    function get(par)
    {
        //获取当前URL
        var local_url = document.location.href;
        //获取要取得的get参数位置
        var get = local_url.indexOf(par +"=");
        if(get == -1){
            return 0;
        }
        //截取字符串
        var get_par = local_url.slice(par.length + get + 1);
        //判断截取后的字符串是否还有其他get参数
        var nextPar = get_par.indexOf("&");
        if(nextPar != -1){
            get_par = get_par.slice(0, nextPar);
        }
        return get_par;
    }

    var isSupportPerformance = (typeof(window.performance) != "undefined");

    if(isSupportPerformance) {
        var t = performance.timing;
    }



    function serialize(obj)
    {
        var s="";
        for(var item in obj)
        {
            s += "&" + item + "=" + obj[item];
        }
        return s;
    }
    //getUserID();
    //getSign();
    //getUserSrc();
    //getUserLandPage();
    window.onload = function(){
        //上报
        if(GLOBAL.configTrackerMethod == 1)
        {
            GLOBAL.Util.sendRequest(GLOBAL.UserInfo);
        }
    };

    GLOBAL.Util.sendRequest = function(userInfo)
    {
        Sjax.load(GLOBAL.configTrackerUrl, {
            data:userInfo,
            success : function(data){
            },
            failure : function(data){
            }
        });
    }

    /**
     *
     * 接口
     * Sjax.load(url, {
 *    data      // 请求参数 (键值对字符串或js对象)
 *    success   // 请求成功回调函数
 *    failure   // 请求失败回调函数
 *    scope     // 回调函数执行上下文
 *    timestamp // 是否加时间戳
 * });
     *
     */
    /*跨域访问*/
    Sjax = function(win){

        var ie678 = !-[1,],
            opera = win.opera,
            doc = win.document,
            head = doc.getElementsByTagName('head')[0],
            timeout = 3000,
            done = false;

        function _serialize(obj){
            var a = [], key, val;
            for(key in obj){
                val = obj[key];
                if(val.constructor == Array){
                    for(var i=0,len=val.length;i<len;i++){
                        a.push(key + '=' + encodeURIComponent(val[i]));
                    }
                }else{
                    a.push(key + '=' + encodeURIComponent(val));
                }
            }
            return a.join('&');
        }
        function request(url,opt){
            function fn(){}
            var opt = opt || {},
                data = opt.data,
                success = opt.success || fn,
                failure = opt.failure || fn,
                scope = opt.scope || win,
                timestamp = opt.timestamp;

            if(data && typeof data == 'object'){
                data = _serialize(data);
            }
            var script = doc.createElement('script');

            function callback(isSucc){
                if(isSucc){
                    if(typeof jsonp != 'undefined'){// 赋值右边的jsonp必须是后台返回的，此变量为全局变量
                        done = true;
                        success.call(scope, jsonp);
                    }else{
                        failure.call(scope);
                        //alert('warning: jsonp did not return.');
                    }
                }else{
                    failure.call(scope);
                }
                // Handle memory leak in IE
                script.onload = script.onerror = script.onreadystatechange = null;
                jsonp = undefined;
                if( head && script.parentNode ){
                    head.removeChild(script);
                }
            }
            function fixOnerror(){
                setTimeout(function(){
                    if(!done){
                        callback();
                    }
                }, timeout);
            }
            if(ie678){
                script.onreadystatechange = function(){
                    var readyState = this.readyState;
                    if(!done && (readyState == 'loaded' || readyState == 'complete')){
                        callback(true);
                    }
                }
                //fixOnerror();
            }else{
                script.onload = function(){
                    callback(true);
                }
                script.onerror = function(){
                    callback();
                }
                if(opera){
                    fixOnerror();
                }
            }
            if(data){
                url += '?' + data;
            }
            if(timestamp){
                if(data){
                    url += '&ts=';
                }else{
                    url += '?ts='
                }
                url += (new Date).getTime();
            }
            script.src = url;
            head.insertBefore(script, head.firstChild);
        }

        return {load:request};
    }(this);

    function getCtime()
    {
        var now = new Date();
        return Math.round(now.getTime() / 1000);
    }

    GLOBAL.UserInfo = {
        'cookieid': getUserID(),
        'referer': getUserRefer(),
        "url": getUserUrl(),
        "ua": getUserAgent(),
        "sign": getSign(),
        "f_time" : getFirstTime(), //第一次访问时间.
        "l_time" : getLastTime(),   //上一次访问时间.
        c_time : getCtime(),
        srcpage : getUserSrc(),
        landpage : getUserLandPage(),
        ip_address : '',
        event_type : 1
    };
    //Sjax.load('http://files.cnblogs.com/snandy/jsonp.js', function(jsonp){
    //    alert(jsonp.name);
    //}, false);
    /*-----------------------------------各类事件上报--------------------------------------------*/
    if (typeof _jfz_paq !== 'object') {
        _jfz_paq = [];
    }

    /*
     * Is property defined?
     */
    function isDefined(property) {
        // workaround https://github.com/douglascrockford/JSLint/commit/24f63ada2f9d7ad65afc90e6d949f631935c2480
        var propertyType = typeof property;

        return propertyType !== 'undefined';
    }

    /*
     * Is property a function?
     */
    function isFunction(property) {
        return typeof property === 'function';
    }

    /*
     * Is property an object?
     *
     * @return bool Returns true if property is null, an Object, or subclass of Object (i.e., an instanceof String, Date, etc.)
     */
    function isObject(property) {
        return typeof property === 'object';
    }

    /*
     * Is property a string?
     */
    function isString(property) {
        return typeof property === 'string' || property instanceof String;
    }

    GLOBAL.configTrackerUrl = 'http://tongji.jinfuzi.cn/ClickStream.php';
    GLOBAL.configTrackerSiteId = '';
    GLOBAL.configTrackerMethod = 1; //1、自动

    /**
     * Applies the given methods in the given order if they are present in paq.
     *
     * @param {Array} paq
     * @param {Array} methodsToApply an array containing method names in the order that they should be applied
     *                 eg ['setSiteId', 'setTrackerUrl']
     * @returns {Array} the modified paq array with the methods that were already applied set to undefined
     */
    function applyMethodsInOrder(paq, methodsToApply)
    {
        var appliedMethods = {};
        var index, iterator;

        for (index = 0; index < methodsToApply.length; index++) {
            var methodNameToApply = methodsToApply[index];
            appliedMethods[methodNameToApply] = 1;

            for (iterator = 0; iterator < paq.length; iterator++) {
                if (paq[iterator] && paq[iterator][0]) {
                    var methodName = paq[iterator][0];

                    if (methodNameToApply === methodName) {
                        apply(paq[iterator]);
                        delete paq[iterator];

                        if (appliedMethods[methodName] > 1) {
                            if (console !== undefined && console && console.error) {
                                console.error('The method ' + methodName + ' is registered more than once in "paq" variable. Only the last call has an effect. Please have a look at the multiple Piwik trackers documentation: http://developer.piwik.org/guides/tracking-javascript-guide#multiple-piwik-trackers');
                            }
                        }

                        appliedMethods[methodName]++;
                    }
                }
            }
        }

        return paq;
    }

    function Tracker(trackerUrl, siteId) {
        var userInfo = GLOBAL.UserInfo;
        return {
            /**
             * Specify the Piwik server URL
             *
             * @param string trackerUrl
             */

            setTrackerUrl: function (trackerUrl) {
                GLOBAL.configTrackerUrl = trackerUrl;
            },

            setSiteId: function (siteId) {
                GLOBAL.configTrackerSiteId = siteId;
                userInfo.site_id = GLOBAL.configTrackerSiteId;
            },

            setTrackerMethod: function (method) {
                GLOBAL.configTrackerMethod = method;
            },

            setUserId: function (userId) {
                userInfo.user_id = userId;
            },

            setIpAddress: function(ip){
                userInfo.ip_address = ip;
            },

            setVisitTime : function(c_time){
                userInfo.c_time = c_time;
                if(GLOBAL.Util.cookie('JRECORD_FTIME') == '' || GLOBAL.Util.cookie('JRECORD_FTIME') == null)
                {
                    GLOBAL.Util.cookie('JRECORD_FTIME',
                        c_time,
                        {
                            expires:10000,
                            domain:GLOBAL.domain,
                            path:'/'
                        }
                    );
                }
                if(GLOBAL.Util.cookie('JRECORD_CTIME') != '')
                {
                    var times = c_time - GLOBAL.Util.cookie('JRECORD_CTIME');
                    if(times >1800)
                    {
                        GLOBAL.Util.cookie('JRECORD_LTIME',
                            GLOBAL.Util.cookie('JRECORD_CTIME'),
                            {
                                expires:10000,
                                domain:GLOBAL.domain,
                                path:'/'
                            }
                        );
                    }
                }else{
                    GLOBAL.Util.cookie('JRECORD_LTIME',
                        GLOBAL.Util.cookie('JRECORD_FTIME'),
                        {
                            expires:10000,
                            domain:GLOBAL.domain,
                            path:'/'
                        }
                    );
                }
                GLOBAL.Util.cookie('JRECORD_CTIME',
                    c_time,
                    {
                        expires:10000,
                        domain:GLOBAL.domain,
                        path:'/'
                    }
                );
                userInfo.l_time = GLOBAL.Util.cookie('JRECORD_LTIME');
                userInfo.f_time = GLOBAL.Util.cookie('JRECORD_FTIME');
            },

            /**
             * Trigger a goal
             *
             * @param int|string idGoal
             */
            trackGoal: function (idGoal) {
                var userinfo = GLOBAL.UserInfo;
                userinfo.event_type = 2;
                userinfo.event_name = idGoal;
                GLOBAL.Util.sendRequest(userinfo);
            },

            /**
             * Manually log a click from your own code
             *
             * @param string sourceUrl
             */
            trackLink: function (sourceUrl) {
                GLOBAL.Util.sendRequest({sourceUrl:sourceUrl,event_type:2});
            },

            trackPageView : function () {
                userInfo.event_type = 1;
                GLOBAL.Util.sendRequest(userInfo);
            }
        }
    }

    GLOBAL.asyncTracker = new Tracker();

    var applyFirst  = ['setTrackerUrl','setSiteId','setUserId','setIpAddress'];
    _jfz_paq = applyMethodsInOrder(_jfz_paq, applyFirst);

    // apply the queue of actions
    for (iterator = 0; iterator < _jfz_paq.length; iterator++) {
        if (_jfz_paq[iterator]) {
            apply(_jfz_paq[iterator]);
        }
    }

    /*
     * apply wrapper
     *
     * @param array parameterArray An array comprising either:
     *      [ 'methodName', optional_parameters ]
     * or:
     *      [ functionObject, optional_parameters ]
     */
    function apply() {
        var i, f, parameterArray;

        for (i = 0; i < arguments.length; i += 1) {
            parameterArray = arguments[i];
            f = parameterArray.shift();

            if (isString(f)) {
                GLOBAL.asyncTracker[f].apply(GLOBAL.asyncTracker, parameterArray);
            } else {
                f.apply(GLOBAL.asyncTracker, parameterArray);
            }
        }
    }

    // replace initialization array with proxy object
    _jfz_paq = new TrackerProxy();
    /************************************************************
     * Proxy object
     * - this allows the caller to continue push()'ing to _jfz_paq
     *   after the Tracker has been initialized and loaded
     ************************************************************/

    function TrackerProxy() {
        return {
            push: apply
        };
    }

    /*-------------------------------------------------------------------------------------------*/
})();