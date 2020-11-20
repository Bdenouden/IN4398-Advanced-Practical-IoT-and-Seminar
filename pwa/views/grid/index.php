<style>
    :root {
        --no-width-col: 7;
        --no-height-col: 4;

        --heatmap-width: calc(100px * var(--no-width-col) + 1px);
        --heatmap-height: calc(100px * var(--no-height-col) + 1px);
    }

    #grid-container {
        height: calc(100px+var(--heatmap-height));
        width: calc(200px + var(--heatmap-width));
        display: inline-block;
        padding: 0 30px;
        margin-top: 40px;
        /* border: solid grey;
        border-radius: 30px; */
        text-align: center;
    }

    #heatmap_1 {
        width: var(--heatmap-width);
        height: var(--heatmap-height);
        display: inline-block;
    }

    #heatmap_1>canvas {
        z-index: -1;
    }

    .static {
        cursor: not-allowed;
    }

    .draggable {
        cursor: move;
    }

    .draggable::before {
        content: "1";
    }

    .grid-legend {
        margin-left: 50px;
        height: var(--heatmap-height);
        display: inline-block;
        position: relative;
    }

    .legend-text {
        display: inline-block;
    }

    .legend-text>.max {
        position: absolute;
        top: 0;
    }

    .legend-text>.middle {
        position: absolute;
        top: 50%;
    }

    #legend-bar_1 {
        width: 20px;
        height: 100%;
        display: inline-block;
    }

    .grid-legend .heatmap-canvas {
        border: solid 1px #a5a5a5;
    }

    .slider {
        margin: 10px 0;
        -webkit-appearance: none;
        width: 200px;
        height: 15px;
        border-radius: 5px;
        background: #d3d3d3;
        outline: none;
        opacity: 0.7;
        -webkit-transition: .2s;
        transition: opacity .2s;
    }

    .slider:hover {
        opacity: 1;
    }

    .slider::-webkit-slider-thumb {
        -webkit-appearance: none;
        appearance: none;
        width: 25px;
        height: 25px;
        border-radius: 50%;
        background: #4CAF50;
        cursor: pointer;
    }

    .slider::-moz-range-thumb {
        width: 25px;
        height: 25px;
        border-radius: 50%;
        background: #4CAF50;
        cursor: pointer;
    }

    .valueContainer {
        display: inline-block;
        width: 120px;
        text-align: left;
        margin-left: 10px;
    }

    .inline-box {
        display: inline-block;
    }

    #snl_search {
        border: 1px solid #ddd;
        border-radius: 4px;
        right: 5px;
        position: absolute;
    }

    .settings-container {
        border: solid 2px gray;
        border-radius: 25px;
        padding: 10px 30px 30px 30px;
        margin: 30px;
        position: relative;
    }

    .settingsIcon {
        font-size: 30pt;
        color: #777;
        display: inline-block;
        position: absolute;
        right: 10px;
        top: 5px;
    }

    .highlight {
        background: #28A745;
        fill: #28A745;
        stroke: black;
    }
</style>

<div class="container" style="text-align: center;">
    <h1 class="text-center">Node map</h1>
    <div id="grid-container">
        <h3 class="text-center"><?= $settings['mapName'] ?></h3>
        <div id="heatmap_1">
            <svg width="100%" height="100%" xmlns="http://www.w3.org/2000/svg" onload="makeDraggable(evt)">
                <defs>
                    <pattern id="smallGrid" width="25" height="25" patternUnits="userSpaceOnUse">
                        <path d="M 25 0 L 0 0 0 25" fill="none" stroke="gray" stroke-width="0.5" />
                    </pattern>
                    <pattern id="grid" width="100" height="100" patternUnits="userSpaceOnUse">
                        <rect width="100" height="100" fill="url(#smallGrid)" />
                        <path d="M 100 0 L 0 0 0 100" fill="none" stroke="gray" stroke-width="1" />
                    </pattern>
                </defs>

                <rect width="100%" height="100%" fill="url(#grid)" />

                <?php foreach ($loc_data as $loc) : ?>
                    <circle item_id="<?= $loc["id"] ?>" class="draggable" cx="<?= $loc['x'] ? $loc['x'] : 10 ?>" cy="<?= $loc['y'] ? $loc['y'] : 10 ?>" r="10" fill="#555" stroke=#fff />
                <?php endforeach ?>
            </svg>
        </div>

        <div class="grid-legend">
            <div id="legend-bar_1"></div>
            <div class="legend-text">
                <p class="max">100%</p>
                <p class="middle">50%</p>
                <p class="min">0%</p>
            </div>
        </div>

    </div>

    <div class="apply-btn">
        <div class="row">
            <button class="btn btn-success col-md-2 offset-md-5" onclick="location.reload()">Refresh map</button>
        </div>
    </div>

    <div class="settings-container">
        <h2>
            Settings
        </h2>
        <div class="settingsIcon">
            <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-gear" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                <path fill-rule="evenodd" d="M8.837 1.626c-.246-.835-1.428-.835-1.674 0l-.094.319A1.873 1.873 0 0 1 4.377 3.06l-.292-.16c-.764-.415-1.6.42-1.184 1.185l.159.292a1.873 1.873 0 0 1-1.115 2.692l-.319.094c-.835.246-.835 1.428 0 1.674l.319.094a1.873 1.873 0 0 1 1.115 2.693l-.16.291c-.415.764.42 1.6 1.185 1.184l.292-.159a1.873 1.873 0 0 1 2.692 1.116l.094.318c.246.835 1.428.835 1.674 0l.094-.319a1.873 1.873 0 0 1 2.693-1.115l.291.16c.764.415 1.6-.42 1.184-1.185l-.159-.291a1.873 1.873 0 0 1 1.116-2.693l.318-.094c.835-.246.835-1.428 0-1.674l-.319-.094a1.873 1.873 0 0 1-1.115-2.692l.16-.292c.415-.764-.42-1.6-1.185-1.184l-.291.159A1.873 1.873 0 0 1 8.93 1.945l-.094-.319zm-2.633-.283c.527-1.79 3.065-1.79 3.592 0l.094.319a.873.873 0 0 0 1.255.52l.292-.16c1.64-.892 3.434.901 2.54 2.541l-.159.292a.873.873 0 0 0 .52 1.255l.319.094c1.79.527 1.79 3.065 0 3.592l-.319.094a.873.873 0 0 0-.52 1.255l.16.292c.893 1.64-.902 3.434-2.541 2.54l-.292-.159a.873.873 0 0 0-1.255.52l-.094.319c-.527 1.79-3.065 1.79-3.592 0l-.094-.319a.873.873 0 0 0-1.255-.52l-.292.16c-1.64.893-3.433-.902-2.54-2.541l.159-.292a.873.873 0 0 0-.52-1.255l-.319-.094c-1.79-.527-1.79-3.065 0-3.592l.319-.094a.873.873 0 0 0 .52-1.255l-.16-.292c-.892-1.64.902-3.433 2.541-2.54l.292.159a.873.873 0 0 0 1.255-.52l.094-.319z" />
                <path fill-rule="evenodd" d="M8 5.754a2.246 2.246 0 1 0 0 4.492 2.246 2.246 0 0 0 0-4.492zM4.754 8a3.246 3.246 0 1 1 6.492 0 3.246 3.246 0 0 1-6.492 0z" />
            </svg>
        </div>
        <div class="form-group">

            <div class="inline-box">
                <label for="mapNameInput">Name: </label>
                <input type="text" id="mapNameInput" placeholder="<?= $settings['mapName'] ?>" style="margin: 0 40px;">
            </div>
            <div class="inline-box">
                <label for="radiusSlider">Radius:</label>
                <input type="range" min="1" max="200" value="<?= $settings['radius'] ?>" class="slider" id="radiusSlider">
                <p class="valueContainer"><span id="radiusValue"><?= $settings['radius'] ?></span> px</p>
            </div>
            <div class="inline-box" style="margin:0  40px;">
                <input type="checkbox" class="form-check-input" id="snapToGrid" <?= $settings['snapToGrid'] == '1' ? 'checked' : '' ?>>
                <label class="form-check-label" for="snapToGrid">snap to grid</label>
            </div>

            <button class="btn-success btn" onclick="saveUserSettings()">Save settings</button>
        </div>

        <div class="card inline-box" style="width: 25rem;height:400px; overflow-y:auto; margin:0 20px; text-align:left">
            <div class="card-header">
               Added sensors <small style="position: absolute; right:5px"><i> (Click to highlight on the map)</i></small>
            </div>
            <ul class="list-group list-group-flush">
                <?php foreach ($loc_data as $loc) : ?>
                    <li class="list-group-item added-sensor" item_id="<?= $loc['id'] ?>" onclick="highlightSelectedNode(this)"><?= $loc['alias'] ? $loc['alias'] : 'unnamed node' ?> <a style="right: 10px; position: absolute;" href="" onclick="deleteSensorFromLoc(this.parentNode)">x</a></li>
                <?php endforeach ?>
            </ul>
        </div>

        <div class="card inline-box" style="width: 25rem;height:400px; overflow-y:auto;margin:0 20px; text-align:left ">
            <div class="card-header">
                Available sensors
                <input type="text" id="snl_search" onkeyup="searchBar(this)" placeholder="Search for alias..">
            </div>
            <ul class="list-group list-group-flush" id="available_sensors">
                <?php foreach ($snl_list as $snl) : ?>
                    <li class="list-group-item available-sensor" item_id="<?= $snl['id'] ?>"><?= $snl['alias'] ? $snl['alias'] : 'unnamed node' ?> <a style="right: 10px; position: absolute;" href="" onclick="addSensorToLoc(this.parentNode)">Add</a></li>
                <?php endforeach ?>
            </ul>
        </div>
    </div>
</div>



<script>
    function saveUserSettings() {
        let name = document.getElementById("mapNameInput").value;
        let reload = name ? true : false; // only reload if name has changed

        $.ajax({
            url: "/grid",
            method: "get",
            data: {
                action: 'settings',
                radius: document.getElementById("radiusSlider").value,
                snap: document.getElementById("snapToGrid").checked,
                mapName: name || "<?= $settings['mapName'] ?>"
            }
        }).done(function(result) {
            if (reload) {
                location.reload();
            }
        })
    }

    function addSensorToLoc(el) {
        $.ajax({
            url: "/grid",
            method: "get",
            data: {
                action: 'add',
                snl_id: el.getAttribute('item_id'),
            }
        }).done(function(result) {
            location.reload();
        })
    }

    function deleteSensorFromLoc(el) {
        $.ajax({
            url: "/grid",
            method: "get",
            data: {
                action: 'delete',
                id: el.getAttribute('item_id'),
            }
        }).done(function() {
            location.reload();
        })
    }

    function searchBar(el) {
        let ul = document.getElementById("available_sensors");
        let filter = el.value.toUpperCase();
        let items = ul.querySelectorAll('li');
        let alias;

        items.forEach(i => {
            alias = i.textContent || i.innerText;
            if (alias.toUpperCase().indexOf(filter) > -1) {
                i.style.display = "";
            } else {
                i.style.display = "none";
            }
        });
    }

    function highlightSelectedNode(el) {
        var items = document.querySelectorAll(".added-sensor");

        var blobs = document.querySelectorAll(".draggable");

        items.forEach(item => {
            if (item == el) {
                item.classList.toggle("highlight");
            } else {
                item.classList.remove("highlight");
            }
        })

        blobs.forEach(blob => {
            if (blob.getAttribute('item_id') == el.getAttribute('item_id')) {
                blob.classList.toggle('highlight');
            } else {
                blob.classList.remove('highlight');
            }
        })

    }

    document.querySelectorAll('.draggable').forEach(item => {
        item.addEventListener('contextmenu', event => {
            event.preventDefault();
            alert('gotcha! X: ' + event.clientX + ", Y: " + event.clientY);
        }, false);
    })

    // https://www.petercollingridge.co.uk/tutorials/svg/interactive/dragging/

    function makeDraggable(evt) {

        let snapToGrid = <?= $settings['snapToGrid'] ? 'true' : 'false' ?>;
        var svg = evt.target;
        var svgpar = svg.parentNode

        snaptogridcb = document.getElementById("snapToGrid");
        snaptogridcb.addEventListener("change", function() {
            snapToGrid = snaptogridcb.checked
        })

        // computer
        svgpar.addEventListener('mousedown', startDrag);
        svgpar.addEventListener('mousemove', drag);
        svgpar.addEventListener('mouseup', endDrag);
        svgpar.addEventListener('mouseleave', endDrag);

        // mobile
        svgpar.addEventListener('touchstart', startDrag);
        svgpar.addEventListener('touchmove', drag);
        svgpar.addEventListener('touchend', endDrag);
        svgpar.addEventListener('touchleave', endDrag);
        svgpar.addEventListener('touchcancel', endDrag);

        var selectedElement, offset;
        var boundaryX1 = 0;
        var boundaryX2 = document.getElementById("grid-container").offsetWidth;
        var boundaryY1 = 0
        var boundaryY2 = document.getElementById("grid-container").offsetHeight;
        var minX, maxX, minY, maxY;

        function startDrag(evt) {



            if (evt.target.classList.contains('draggable')) {
                selectedElement = evt.target;

                var bbox = selectedElement.getBBox();

                minX = boundaryX1 + bbox.width / 2;
                maxX = boundaryX2 - bbox.width / 2;
                minY = boundaryY1 + bbox.height / 2;
                maxY = boundaryY2 - bbox.height / 2;

                offset = getMousePosition(evt);
                offset.x -= parseFloat(selectedElement.getAttributeNS(null, "cx"));
                offset.y -= parseFloat(selectedElement.getAttributeNS(null, "cy"));
            }
        }

        function drag(evt) {
            if (selectedElement) {
                evt.preventDefault();
                var coord = getMousePosition(evt);
                var posX = coord.x - offset.x;
                var posY = coord.y - offset.y;

                var snapOffset = 0;
                if (snapToGrid) {
                    posX = Math.round(posX / 25) * 25;
                    posY = Math.round(posY / 25) * 25;
                    snapOffset = 15;
                }

                if (posX >= maxX) {
                    posX = maxX - snapOffset;
                } else if (posX <= minX) {
                    posX = minX + snapOffset;
                }
                if (posY >= maxY) {
                    posY = maxY - snapOffset;
                } else if (posY <= minY) {
                    posY = minY + snapOffset;
                }

                selectedElement.setAttributeNS(null, "cx", posX);
                selectedElement.setAttributeNS(null, "cy", posY);

            }

        }

        function endDrag(evt) {
            // send ajax to update position in db
            if (selectedElement) {
                $.ajax({
                    url: "/grid",
                    method: "post",
                    data: {
                        id: selectedElement.getAttributeNS(null, "item_id"),
                        x: evt.target.getAttributeNS(null, "cx"),
                        y: selectedElement.getAttributeNS(null, "cy")
                    }
                }).done(function() {
                    // alert('updated!');
                })
            }
            selectedElement = false;
        }

        function getMousePosition(evt) {
            var CTM = svg.getScreenCTM();
            if (evt.touches) {
                evt = evt.touches[0];
            }
            return {
                x: (evt.clientX - CTM.e) / CTM.a,
                y: (evt.clientY - CTM.f) / CTM.d
            };
        }
    }
</script>

<script>
    /*
     * heatmap.js v2.0.5 | JavaScript Heatmap Library
     *
     * Copyright 2008-2016 Patrick Wied <heatmapjs@patrick-wied.at> - All rights reserved.
     * Dual licensed under MIT and Beerware license 
     *
     * :: 2016-09-05 01:16
     */
    (function(a, b, c) {
        if (typeof module !== "undefined" && module.exports) {
            module.exports = c()
        } else if (typeof define === "function" && define.amd) {
            define(c)
        } else {
            b[a] = c()
        }
    })("h337", this, function() {
        var a = {
            defaultRadius: 40,
            defaultRenderer: "canvas2d",
            defaultGradient: {
                .25: "rgb(0,0,255)",
                .55: "rgb(0,255,0)",
                .85: "yellow",
                1: "rgb(255,0,0)"
            },
            defaultMaxOpacity: 1,
            defaultMinOpacity: 0,
            defaultBlur: .85,
            defaultXField: "x",
            defaultYField: "y",
            defaultValueField: "value",
            plugins: {}
        };
        var b = function h() {
            var b = function d(a) {
                this._coordinator = {};
                this._data = [];
                this._radi = [];
                this._min = 10;
                this._max = 1;
                this._xField = a["xField"] || a.defaultXField;
                this._yField = a["yField"] || a.defaultYField;
                this._valueField = a["valueField"] || a.defaultValueField;
                if (a["radius"]) {
                    this._cfgRadius = a["radius"]
                }
            };
            var c = a.defaultRadius;
            b.prototype = {
                _organiseData: function(a, b) {
                    var d = a[this._xField];
                    var e = a[this._yField];
                    var f = this._radi;
                    var g = this._data;
                    var h = this._max;
                    var i = this._min;
                    var j = a[this._valueField] || 1;
                    var k = a.radius || this._cfgRadius || c;
                    if (!g[d]) {
                        g[d] = [];
                        f[d] = []
                    }
                    if (!g[d][e]) {
                        g[d][e] = j;
                        f[d][e] = k
                    } else {
                        g[d][e] += j
                    }
                    var l = g[d][e];
                    if (l > h) {
                        if (!b) {
                            this._max = l
                        } else {
                            this.setDataMax(l)
                        }
                        return false
                    } else if (l < i) {
                        if (!b) {
                            this._min = l
                        } else {
                            this.setDataMin(l)
                        }
                        return false
                    } else {
                        return {
                            x: d,
                            y: e,
                            value: j,
                            radius: k,
                            min: i,
                            max: h
                        }
                    }
                },
                _unOrganizeData: function() {
                    var a = [];
                    var b = this._data;
                    var c = this._radi;
                    for (var d in b) {
                        for (var e in b[d]) {
                            a.push({
                                x: d,
                                y: e,
                                radius: c[d][e],
                                value: b[d][e]
                            })
                        }
                    }
                    return {
                        min: this._min,
                        max: this._max,
                        data: a
                    }
                },
                _onExtremaChange: function() {
                    this._coordinator.emit("extremachange", {
                        min: this._min,
                        max: this._max
                    })
                },
                addData: function() {
                    if (arguments[0].length > 0) {
                        var a = arguments[0];
                        var b = a.length;
                        while (b--) {
                            this.addData.call(this, a[b])
                        }
                    } else {
                        var c = this._organiseData(arguments[0], true);
                        if (c) {
                            if (this._data.length === 0) {
                                this._min = this._max = c.value
                            }
                            this._coordinator.emit("renderpartial", {
                                min: this._min,
                                max: this._max,
                                data: [c]
                            })
                        }
                    }
                    return this
                },
                setData: function(a) {
                    var b = a.data;
                    var c = b.length;
                    this._data = [];
                    this._radi = [];
                    for (var d = 0; d < c; d++) {
                        this._organiseData(b[d], false)
                    }
                    this._max = a.max;
                    this._min = a.min || 0;
                    this._onExtremaChange();
                    this._coordinator.emit("renderall", this._getInternalData());
                    return this
                },
                removeData: function() {},
                setDataMax: function(a) {
                    this._max = a;
                    this._onExtremaChange();
                    this._coordinator.emit("renderall", this._getInternalData());
                    return this
                },
                setDataMin: function(a) {
                    this._min = a;
                    this._onExtremaChange();
                    this._coordinator.emit("renderall", this._getInternalData());
                    return this
                },
                setCoordinator: function(a) {
                    this._coordinator = a
                },
                _getInternalData: function() {
                    return {
                        max: this._max,
                        min: this._min,
                        data: this._data,
                        radi: this._radi
                    }
                },
                getData: function() {
                    return this._unOrganizeData()
                }
            };
            return b
        }();
        var c = function i() {
            var a = function(a) {
                var b = a.gradient || a.defaultGradient;
                var c = document.createElement("canvas");
                var d = c.getContext("2d");
                c.width = 256;
                c.height = 1;
                var e = d.createLinearGradient(0, 0, 256, 1);
                for (var f in b) {
                    e.addColorStop(f, b[f])
                }
                d.fillStyle = e;
                d.fillRect(0, 0, 256, 1);
                return d.getImageData(0, 0, 256, 1).data
            };
            var b = function(a, b) {
                var c = document.createElement("canvas");
                var d = c.getContext("2d");
                var e = a;
                var f = a;
                c.width = c.height = a * 2;
                if (b == 1) {
                    d.beginPath();
                    d.arc(e, f, a, 0, 2 * Math.PI, false);
                    d.fillStyle = "rgba(0,0,0,1)";
                    d.fill()
                } else {
                    var g = d.createRadialGradient(e, f, a * b, e, f, a);
                    g.addColorStop(0, "rgba(0,0,0,1)");
                    g.addColorStop(1, "rgba(0,0,0,0)");
                    d.fillStyle = g;
                    d.fillRect(0, 0, 2 * a, 2 * a)
                }
                return c
            };
            var c = function(a) {
                var b = [];
                var c = a.min;
                var d = a.max;
                var e = a.radi;
                var a = a.data;
                var f = Object.keys(a);
                var g = f.length;
                while (g--) {
                    var h = f[g];
                    var i = Object.keys(a[h]);
                    var j = i.length;
                    while (j--) {
                        var k = i[j];
                        var l = a[h][k];
                        var m = e[h][k];
                        b.push({
                            x: h,
                            y: k,
                            value: l,
                            radius: m
                        })
                    }
                }
                return {
                    min: c,
                    max: d,
                    data: b
                }
            };

            function d(b) {
                var c = b.container;
                var d = this.shadowCanvas = document.createElement("canvas");
                var e = this.canvas = b.canvas || document.createElement("canvas");
                var f = this._renderBoundaries = [1e4, 1e4, 0, 0];
                var g = getComputedStyle(b.container) || {};
                e.className = "heatmap-canvas";
                this._width = e.width = d.width = b.width || +g.width.replace(/px/, "");
                this._height = e.height = d.height = b.height || +g.height.replace(/px/, "");
                this.shadowCtx = d.getContext("2d");
                this.ctx = e.getContext("2d");
                e.style.cssText = d.style.cssText = "position:absolute;left:0;top:0;";
                c.style.position = "relative";
                c.appendChild(e);
                this._palette = a(b);
                this._templates = {};
                this._setStyles(b)
            }
            d.prototype = {
                renderPartial: function(a) {
                    if (a.data.length > 0) {
                        this._drawAlpha(a);
                        this._colorize()
                    }
                },
                renderAll: function(a) {
                    this._clear();
                    if (a.data.length > 0) {
                        this._drawAlpha(c(a));
                        this._colorize()
                    }
                },
                _updateGradient: function(b) {
                    this._palette = a(b)
                },
                updateConfig: function(a) {
                    if (a["gradient"]) {
                        this._updateGradient(a)
                    }
                    this._setStyles(a)
                },
                setDimensions: function(a, b) {
                    this._width = a;
                    this._height = b;
                    this.canvas.width = this.shadowCanvas.width = a;
                    this.canvas.height = this.shadowCanvas.height = b
                },
                _clear: function() {
                    this.shadowCtx.clearRect(0, 0, this._width, this._height);
                    this.ctx.clearRect(0, 0, this._width, this._height)
                },
                _setStyles: function(a) {
                    this._blur = a.blur == 0 ? 0 : a.blur || a.defaultBlur;
                    if (a.backgroundColor) {
                        this.canvas.style.backgroundColor = a.backgroundColor
                    }
                    this._width = this.canvas.width = this.shadowCanvas.width = a.width || this._width;
                    this._height = this.canvas.height = this.shadowCanvas.height = a.height || this._height;
                    this._opacity = (a.opacity || 0) * 255;
                    this._maxOpacity = (a.maxOpacity || a.defaultMaxOpacity) * 255;
                    this._minOpacity = (a.minOpacity || a.defaultMinOpacity) * 255;
                    this._useGradientOpacity = !!a.useGradientOpacity
                },
                _drawAlpha: function(a) {
                    var c = this._min = a.min;
                    var d = this._max = a.max;
                    var a = a.data || [];
                    var e = a.length;
                    var f = 1 - this._blur;
                    while (e--) {
                        var g = a[e];
                        var h = g.x;
                        var i = g.y;
                        var j = g.radius;
                        var k = Math.min(g.value, d);
                        var l = h - j;
                        var m = i - j;
                        var n = this.shadowCtx;
                        var o;
                        if (!this._templates[j]) {
                            this._templates[j] = o = b(j, f)
                        } else {
                            o = this._templates[j]
                        }
                        var p = (k - c) / (d - c);
                        n.globalAlpha = p < .01 ? .01 : p;
                        n.drawImage(o, l, m);
                        if (l < this._renderBoundaries[0]) {
                            this._renderBoundaries[0] = l
                        }
                        if (m < this._renderBoundaries[1]) {
                            this._renderBoundaries[1] = m
                        }
                        if (l + 2 * j > this._renderBoundaries[2]) {
                            this._renderBoundaries[2] = l + 2 * j
                        }
                        if (m + 2 * j > this._renderBoundaries[3]) {
                            this._renderBoundaries[3] = m + 2 * j
                        }
                    }
                },
                _colorize: function() {
                    var a = this._renderBoundaries[0];
                    var b = this._renderBoundaries[1];
                    var c = this._renderBoundaries[2] - a;
                    var d = this._renderBoundaries[3] - b;
                    var e = this._width;
                    var f = this._height;
                    var g = this._opacity;
                    var h = this._maxOpacity;
                    var i = this._minOpacity;
                    var j = this._useGradientOpacity;
                    if (a < 0) {
                        a = 0
                    }
                    if (b < 0) {
                        b = 0
                    }
                    if (a + c > e) {
                        c = e - a
                    }
                    if (b + d > f) {
                        d = f - b
                    }
                    var k = this.shadowCtx.getImageData(a, b, c, d);
                    var l = k.data;
                    var m = l.length;
                    var n = this._palette;
                    for (var o = 3; o < m; o += 4) {
                        var p = l[o];
                        var q = p * 4;
                        if (!q) {
                            continue
                        }
                        var r;
                        if (g > 0) {
                            r = g
                        } else {
                            if (p < h) {
                                if (p < i) {
                                    r = i
                                } else {
                                    r = p
                                }
                            } else {
                                r = h
                            }
                        }
                        l[o - 3] = n[q];
                        l[o - 2] = n[q + 1];
                        l[o - 1] = n[q + 2];
                        l[o] = j ? n[q + 3] : r
                    }
                    k.data = l;
                    this.ctx.putImageData(k, a, b);
                    this._renderBoundaries = [1e3, 1e3, 0, 0]
                },
                getValueAt: function(a) {
                    var b;
                    var c = this.shadowCtx;
                    var d = c.getImageData(a.x, a.y, 1, 1);
                    var e = d.data[3];
                    var f = this._max;
                    var g = this._min;
                    b = Math.abs(f - g) * (e / 255) >> 0;
                    return b
                },
                getDataURL: function() {
                    return this.canvas.toDataURL()
                }
            };
            return d
        }();
        var d = function j() {
            var b = false;
            if (a["defaultRenderer"] === "canvas2d") {
                b = c
            }
            return b
        }();
        var e = {
            merge: function() {
                var a = {};
                var b = arguments.length;
                for (var c = 0; c < b; c++) {
                    var d = arguments[c];
                    for (var e in d) {
                        a[e] = d[e]
                    }
                }
                return a
            }
        };
        var f = function k() {
            var c = function h() {
                function a() {
                    this.cStore = {}
                }
                a.prototype = {
                    on: function(a, b, c) {
                        var d = this.cStore;
                        if (!d[a]) {
                            d[a] = []
                        }
                        d[a].push(function(a) {
                            return b.call(c, a)
                        })
                    },
                    emit: function(a, b) {
                        var c = this.cStore;
                        if (c[a]) {
                            var d = c[a].length;
                            for (var e = 0; e < d; e++) {
                                var f = c[a][e];
                                f(b)
                            }
                        }
                    }
                };
                return a
            }();
            var f = function(a) {
                var b = a._renderer;
                var c = a._coordinator;
                var d = a._store;
                c.on("renderpartial", b.renderPartial, b);
                c.on("renderall", b.renderAll, b);
                c.on("extremachange", function(b) {
                    a._config.onExtremaChange && a._config.onExtremaChange({
                        min: b.min,
                        max: b.max,
                        gradient: a._config["gradient"] || a._config["defaultGradient"]
                    })
                });
                d.setCoordinator(c)
            };

            function g() {
                var g = this._config = e.merge(a, arguments[0] || {});
                this._coordinator = new c;
                if (g["plugin"]) {
                    var h = g["plugin"];
                    if (!a.plugins[h]) {
                        throw new Error("Plugin '" + h + "' not found. Maybe it was not registered.")
                    } else {
                        var i = a.plugins[h];
                        this._renderer = new i.renderer(g);
                        this._store = new i.store(g)
                    }
                } else {
                    this._renderer = new d(g);
                    this._store = new b(g)
                }
                f(this)
            }
            g.prototype = {
                addData: function() {
                    this._store.addData.apply(this._store, arguments);
                    return this
                },
                removeData: function() {
                    this._store.removeData && this._store.removeData.apply(this._store, arguments);
                    return this
                },
                setData: function() {
                    this._store.setData.apply(this._store, arguments);
                    return this
                },
                setDataMax: function() {
                    this._store.setDataMax.apply(this._store, arguments);
                    return this
                },
                setDataMin: function() {
                    this._store.setDataMin.apply(this._store, arguments);
                    return this
                },
                configure: function(a) {
                    this._config = e.merge(this._config, a);
                    this._renderer.updateConfig(this._config);
                    this._coordinator.emit("renderall", this._store._getInternalData());
                    return this
                },
                repaint: function() {
                    this._coordinator.emit("renderall", this._store._getInternalData());
                    return this
                },
                getData: function() {
                    return this._store.getData()
                },
                getDataURL: function() {
                    return this._renderer.getDataURL()
                },
                getValueAt: function(a) {
                    if (this._store.getValueAt) {
                        return this._store.getValueAt(a)
                    } else if (this._renderer.getValueAt) {
                        return this._renderer.getValueAt(a)
                    } else {
                        return null
                    }
                }
            };
            return g
        }();
        var g = {
            create: function(a) {
                return new f(a)
            },
            register: function(b, c) {
                a.plugins[b] = c
            }
        };
        return g
    });
</script>

<script>
    let bar = document.getElementById("legend-bar_1")
    var legend = h337.create({
        container: bar,
        radius: bar.offsetHeight
    })

    legend.setData({
        max: 5,
        data: [{
            x: 10,
            y: 0,
            value: 5
        }]
    });


    let slider = document.getElementById("radiusSlider")

    var heatmap = h337.create({
        container: document.getElementById("heatmap_1"),
        radius: <?= $settings['radius'] ?>
    });

    slider.addEventListener('input', function() {
        document.getElementById("radiusValue").innerText = slider.value;
        heatmap.setData({
            max: 100,
            data: [
                <?php foreach ($loc_data as $loc) : ?> {
                        x: <?= $loc['x'] ?>,
                        y: <?= $loc['y'] ?>,
                        value: <?= $loc['value'] ?>,
                        radius: slider.value
                    },
                <?php endforeach ?>
            ]
        })

    })



    heatmap.setData({
        max: 100,
        data: [
            <?php foreach ($loc_data as $loc) : ?> {
                    x: <?= $loc['x'] ?>,
                    y: <?= $loc['y'] ?>,
                    value: <?= $loc['value'] ?>
                },
            <?php endforeach ?>
        ]
    })
</script>