/**
$Id: Iuppiter.js 3026 2010-06-23 10:03:13Z Bear $

Copyright (c) 2010 Nuwa Information Co., Ltd, and individual contributors.
All rights reserved.

Redistribution and use in source and binary forms, with or without
modification, are permitted provided that the following conditions are met:

  1. Redistributions of source code must retain the above copyright notice,
     this list of conditions and the following disclaimer.

  2. Redistributions in binary form must reproduce the above copyright
     notice, this list of conditions and the following disclaimer in the
     documentation and/or other materials provided with the distribution.

  3. Neither the name of Nuwa Information nor the names of its contributors
     may be used to endorse or promote products derived from this software
     without specific prior written permission.

THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS"
AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE
IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE
DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT OWNER OR CONTRIBUTORS BE LIABLE
FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL
DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR
SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER
CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY,
OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE
OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.

$Author: Bear $
$Date: 2010-06-23 18:03:13 +0800 (星期三, 23 六月 2010) $
$Revision: 3026 $
*/
var fastcompressor = {};
(function (k) {
    k.toByteArray = function (c) {
        var h = [],
            b, a;
        for (b = 0; b < c.length; b++) a = c.charCodeAt(b), 127 >= a ? h.push(a) : (2047 >= a ? h.push(a >> 6 | 192) : (65535 >= a ? h.push(a >> 12 | 224) : (h.push(a >> 18 | 240), h.push(a >> 12 & 63 | 128)), h.push(a >> 6 & 63 | 128)), h.push(a & 63 | 128));
        return h
    };
    k.Base64 = {
        CA: "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/",
        CAS: "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789-_",
        IA: Array(256),
        IAS: Array(256),
        init: function () {
            var c;
            for (c = 0; 256 > c; c++) k.Base64.IA[c] = -1, k.Base64.IAS[c] = -1;
            c = 0;
            for (iS = k.Base64.CA.length; c < iS; c++) k.Base64.IA[k.Base64.CA.charCodeAt(c)] = c, k.Base64.IAS[k.Base64.CAS.charCodeAt(c)] = c;
            k.Base64.IA["="] = k.Base64.IAS["="] = 0
        },
        encode: function (c, h) {
            var b, a, d, e, m, g, f, l, j;
            b = h ? k.Base64.CAS : k.Base64.CA;
            d = c.constructor == Array ? c : k.toByteArray(c);
            e = d.length;
            m = 3 * (e / 3);
            g = (e - 1) / 3 + 1 << 2;
            a = Array(g);
            for (l = f = 0; f < m;) j = (d[f++] & 255) << 16 | (d[f++] & 255) << 8 | d[f++] & 255, a[l++] = b.charAt(j >> 18 & 63), a[l++] = b.charAt(j >> 12 & 63), a[l++] = b.charAt(j >> 6 & 63), a[l++] = b.charAt(j & 63);
            f = e - m;
            0 < f && (j = (d[m] &
                255) << 10 | (2 == f ? (d[e - 1] & 255) << 2 : 0), a[g - 4] = b.charAt(j >> 12), a[g - 3] = b.charAt(j >> 6 & 63), a[g - 2] = 2 == f ? b.charAt(j & 63) : "=", a[g - 1] = "=");
            return a.join("")
        },
        decode: function (c, h) {
            var b, a, d, e, m, g, f, l, j, p, q, n;
            b = h ? k.Base64.IAS : k.Base64.IA;
            c.constructor == Array ? (d = c, m = !0) : (d = k.toByteArray(c), m = !1);
            e = d.length;
            g = 0;
            for (f = e - 1; g < f && 0 > b[d[g]];) g++;
            for (; 0 < f && 0 > b[d[f]];) f--;
            l = "=" == d[f] ? "=" == d[f - 1] ? 2 : 1 : 0;
            a = f - g + 1;
            j = 76 < e ? ("\r" == d[76] ? a / 78 : 0) << 1 : 0;
            e = (6 * (a - j) >> 3) - l;
            a = Array(e);
            q = p = 0;
            for (eLen = 3 * (e / 3); p < eLen;) n = b[d[g++]] << 18 | b[d[g++]] <<
                12 | b[d[g++]] << 6 | b[d[g++]], a[p++] = n >> 16 & 255, a[p++] = n >> 8 & 255, a[p++] = n & 255, 0 < j && 19 == ++q && (g += 2, q = 0);
            if (p < e) {
                for (j = n = 0; g <= f - l; j++) n |= b[d[g++]] << 18 - 6 * j;
                for (b = 16; p < e; b -= 8) a[p++] = n >> b & 255
            }
            if (m) return a;
            for (n = 0; n < a.length; n++) a[n] = String.fromCharCode(a[n]);
            return a.join("")
        }
    };
    k.Base64.init();
    NBBY = 8;
    MATCH_BITS = 6;
    MATCH_MIN = 3;
    MATCH_MAX = (1 << MATCH_BITS) + (MATCH_MIN - 1);
    OFFSET_MASK = (1 << 16 - MATCH_BITS) - 1;
    LEMPEL_SIZE = 256;
    k.compress = function (c) {
        var h = [],
            b, a = 0,
            d = 0,
            e, m, g = 1 << NBBY - 1,
            f, l, j = Array(LEMPEL_SIZE);
        for (b = 0; b < LEMPEL_SIZE; b++) j[b] =
            3435973836;
        c = c.constructor == Array ? c : k.toByteArray(c);
        for (b = c.length; a < b;) {
            if ((g <<= 1) == 1 << NBBY) {
                if (d >= b - 1 - 2 * NBBY) {
                    f = b;
                    for (d = a = 0; f; f--) h[d++] = c[a++];
                    break
                }
                g = 1;
                m = d;
                h[d++] = 0
            }
            if (a > b - MATCH_MAX) h[d++] = c[a++];
            else if (e = (c[a] + 13 ^ c[a + 1] - 13 ^ c[a + 2]) & LEMPEL_SIZE - 1, l = a - j[e] & OFFSET_MASK, j[e] = a, e = a - l, 0 <= e && e != a && c[a] == c[e] && c[a + 1] == c[e + 1] && c[a + 2] == c[e + 2]) {
                h[m] |= g;
                for (f = MATCH_MIN; f < MATCH_MAX && c[a + f] == c[e + f]; f++);
                h[d++] = f - MATCH_MIN << NBBY - MATCH_BITS | l >> NBBY;
                h[d++] = l;
                a += f
            } else h[d++] = c[a++]
        }
        return h
    };
    k.decompress = function (c,
        h) {
        var b, a = [],
            d, e = 0,
            m = 0,
            g, f, l = 1 << NBBY - 1,
            j;
        b = c.constructor == Array ? c : k.toByteArray(c);
        for (d = b.length; e < d;) {
            if ((l <<= 1) == 1 << NBBY) l = 1, f = b[e++];
            if (f & l)
                if (j = (b[e] >> NBBY - MATCH_BITS) + MATCH_MIN, g = (b[e] << NBBY | b[e + 1]) & OFFSET_MASK, e += 2, 0 <= (g = m - g))
                    for (; 0 <= --j;) a[m++] = a[g++];
                else break;
                else a[m++] = b[e++]
        }
        if (!("undefined" == typeof h ? 0 : h)) {
            for (b = 0; b < m; b++) a[b] = String.fromCharCode(a[b]);
            a = a.join("")
        }
        return a
    }
})(fastcompressor);