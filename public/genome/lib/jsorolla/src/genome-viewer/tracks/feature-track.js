/*
 * Copyright (c) 2012 Francisco Salavert (ICM-CIPF)
 * Copyright (c) 2012 Ruben Sanchez (ICM-CIPF)
 * Copyright (c) 2012 Ignacio Medina (ICM-CIPF)
 *
 * This file is part of JS Common Libs.
 *
 * JS Common Libs is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 2 of the License, or
 * (at your option) any later version.
 *
 * JS Common Libs is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with JS Common Libs. If not, see <http://www.gnu.org/licenses/>.
 */

FeatureTrack.prototype = new Track({});

function FeatureTrack(args) {
    Track.call(this, args);

    // Using Underscore 'extend' function to extend and add Backbone Events
    _.extend(this, Backbone.Events);

    //set default args

    //save default render reference;
    this.defaultRenderer = this.renderer;
//    this.histogramRenderer = new FeatureClusterRenderer();
    this.histogramRenderer = new HistogramRenderer(args);

    this.featureType = 'Feature';
    //set instantiation args, must be last
    _.extend(this, args);


    this.resource = this.dataAdapter.resource;
    this.species = this.dataAdapter.species;

    this.dataType = 'features';
};


FeatureTrack.prototype.clean = function () {
    this._clean();

//    console.time("-----------------------------------------empty");
    while (this.svgCanvasFeatures.firstChild) {
        this.svgCanvasFeatures.removeChild(this.svgCanvasFeatures.firstChild);
    }
//    console.timeEnd("-----------------------------------------empty");
};

FeatureTrack.prototype.updateHeight = function () {
    //this._updateHeight();
    if (this.histogram) {
        this.contentDiv.style.height = this.histogramRenderer.histogramHeight + 5 + 'px';
        this.main.setAttribute('height', this.histogramRenderer.histogramHeight);
        return;
    }

    var renderedHeight = this.svgCanvasFeatures.getBoundingClientRect().height;
    this.main.setAttribute('height', renderedHeight);

    if (this.resizable) {
        if (this.autoHeight == false) {
            this.contentDiv.style.height = this.height + 10 + 'px';
        } else if (this.autoHeight == true) {
            var x = this.pixelPosition;
            var width = this.width;
            var lastContains = 0;
            for (var i in this.renderedArea) {
                if (this.renderedArea[i].contains({
                        start: x,
                        end: x + width
                    })) {
                    lastContains = i;
                }
            }
            var visibleHeight = parseInt(lastContains) + 30;
            this.contentDiv.style.height = visibleHeight + 10 + 'px';
            this.main.setAttribute('height', visibleHeight);
        }
    }
};

FeatureTrack.prototype.setWidth = function(width) {
    this._setWidth(width);
    this.main.setAttribute("width", this.width);
};

FeatureTrack.prototype.initializeDom = function (targetId) {
    this._initializeDom(targetId);

    this.main = SVG.addChild(this.contentDiv, 'svg', {
        'class': 'trackSvg',
        'x': 0,
        'y': 0,
        'width': this.width
    });
    this.svgCanvasFeatures = SVG.addChild(this.main, 'svg', {
        'class': 'features',
        'x': -this.pixelPosition,
        'width': this.svgCanvasWidth
    });
    this.updateHeight();
};

FeatureTrack.prototype.render = function (targetId) {
    this.initializeDom(targetId);

    this.svgCanvasOffset = (this.width * 3 / 2) / this.pixelBase;
    this.svgCanvasLeftLimit = this.region.start - this.svgCanvasOffset * 2;
    this.svgCanvasRightLimit = this.region.start + this.svgCanvasOffset * 2
};

FeatureTrack.prototype.getDataHandler = function (event) {
    var features;
    if (event.dataType == 'histogram') {
        this.renderer = this.histogramRenderer;
        features = event.items;
    } else {
        this.renderer = this.defaultRenderer;
        features = this.getFeaturesToRenderByChunk(event);
    }
    this.renderer.render(features, {
        cacheItems: event.items,
        svgCanvasFeatures: this.svgCanvasFeatures,
        featureTypes: this.featureTypes,
        renderedArea: this.renderedArea,
        pixelBase: this.pixelBase,
        position: this.region.center(),
        regionSize: this.region.length(),
        maxLabelRegionSize: this.maxLabelRegionSize,
        width: this.width,
        pixelPosition: this.pixelPosition,
        resource: this.resource,
        species: this.species,
        featureType: this.featureType
    });
    this.updateHeight();
};

FeatureTrack.prototype.draw = function () {
    var _this = this;

    this.svgCanvasOffset = (this.width * 3 / 2) / this.pixelBase;
    this.svgCanvasLeftLimit = this.region.start - this.svgCanvasOffset * 2;
    this.svgCanvasRightLimit = this.region.start + this.svgCanvasOffset * 2;

    this.updateHistogramParams();
    this.clean();

    this.dataType = 'features';
    if (this.histogram) {
        this.dataType = 'histogram';
    }


    if (typeof this.visibleRegionSize === 'undefined' || this.region.length() < this.visibleRegionSize) {
        this.setLoading(true);
        this.dataAdapter.getData({
            dataType: this.dataType,
            region: new Region({
                chromosome: this.region.chromosome,
                start: this.region.start - this.svgCanvasOffset * 2,
                end: this.region.end + this.svgCanvasOffset * 2
            }),
            params: {
                histogram: this.histogram,
                histogramLogarithm: this.histogramLogarithm,
                histogramMax: this.histogramMax,
                interval: this.interval
            },
            done: function (event) {
                _this.getDataHandler(event);
                _this.setLoading(false);
            }
        });

//        this.invalidZoomText.setAttribute("visibility", "hidden");
    } else {
//        this.invalidZoomText.setAttribute("visibility", "visible");
    }
    this.updateHeight();
};


FeatureTrack.prototype.move = function (disp) {
    var _this = this;

    this.dataType = 'features';
    if (this.histogram) {
        this.dataType = 'histogram';
    }

    _this.region.center();
    var pixelDisplacement = disp * _this.pixelBase;
    this.pixelPosition -= pixelDisplacement;

    //parseFloat important
    var move = parseFloat(this.svgCanvasFeatures.getAttribute("x")) + pixelDisplacement;
    this.svgCanvasFeatures.setAttribute("x", move);

    var virtualStart = parseInt(this.region.start - this.svgCanvasOffset);
    var virtualEnd = parseInt(this.region.end + this.svgCanvasOffset);

    if (typeof this.visibleRegionSize === 'undefined' || this.region.length() < this.visibleRegionSize) {

        if (disp > 0 && virtualStart < this.svgCanvasLeftLimit) {
            this.dataAdapter.getData({
                dataType: this.dataType,
                region: new Region({
                    chromosome: _this.region.chromosome,
                    start: parseInt(this.svgCanvasLeftLimit - this.svgCanvasOffset),
                    end: this.svgCanvasLeftLimit
                }),
                params: {
                    histogram: this.histogram,
                    histogramLogarithm: this.histogramLogarithm,
                    histogramMax: this.histogramMax,
                    interval: this.interval
                },
                done: function (event) {
                    _this.getDataHandler(event);
                }
            });
            this.svgCanvasLeftLimit = parseInt(this.svgCanvasLeftLimit - this.svgCanvasOffset);
        }

        if (disp < 0 && virtualEnd > this.svgCanvasRightLimit) {
            this.dataAdapter.getData({
                dataType: this.dataType,
                region: new Region({
                    chromosome: _this.region.chromosome,
                    start: this.svgCanvasRightLimit,
                    end: parseInt(this.svgCanvasRightLimit + this.svgCanvasOffset)
                }),
                params: {
                    histogram: this.histogram,
                    histogramLogarithm: this.histogramLogarithm,
                    histogramMax: this.histogramMax,
                    interval: this.interval
                },
                done: function (event) {
                    _this.getDataHandler(event);
                }

            });
            this.svgCanvasRightLimit = parseInt(this.svgCanvasRightLimit + this.svgCanvasOffset);
        }
    }

    if (this.autoHeight == true) {
        this.updateHeight();
    }
};
