(function ($) {
    $.fn.flexGraph = function () {

        /**
         * 
         * @type type
         */
        var detectedFactory = null;

        /**
         * 
         * @param {type} graph
         * @returns {undefined}
         */
        function renderGraph(graph) {
            //check if rendering library is defined and detect if not
            if (!graph.settings.factory) {
                graph.settings.factory = detectFactory();
            }

            //set element css attributes
            graph.element.css({
                width: graph.settings.width,
                height: graph.settings.height
            });

            if (graph.data.source) { //ajax data
                $.ajax(graph.data.source, {
                    method: 'POST',
                    dataType: 'json',
                    data: graph.data.postData || {},
                    beforeSend: function () {
                        graph.element.append($('<div/>', {class: 'loader-cube'}));
                        return true;
                    },
                    success: function (response) {
                        graph.data = response;
                        decorateGraph(graph);
                    },
                    error: function () {
                        //Failed to load graph message
                    },
                    complete: function () {
                        //hide loader
                        $('.loader-cube', graph.element).remove();
                    }
                });
            } else {
                decorateGraph(graph);
            }
        }

        /**
         * 
         * @param {type} graph
         * @returns {undefined}
         */
        function decorateGraph(graph) {
            //execute rendering
            switch (graph.settings.factory.name) {
                case 'Morris':
                    decorateMorrisData(graph);
                    break;

                default:
                    break;
            }
        }

        /**
         * 
         * @returns {undefined}
         */
        function detectFactory() {
            if (detectedFactory === null) {
                if (typeof window['Morris'] !== 'undefined') {
                    detectedFactory = {
                        name: 'Morris',
                        version: null
                    };
                } else {
                    throw new Error('Graph library was not detected');
                }
            }

            return detectedFactory;
        }

        /**
         * 
         * @param {type} graph
         * @returns {undefined}
         */
        function decorateMorrisData(graph) {
            //prepare the graph options
            var options = {};

            if (!graph.data.element) { //add element id if not specified
                if (!graph.element.attr('id')) {
                    graph.element.attr('id', Math.random().toString(36).substring(7));
                }
                options.element = graph.element.attr('id');
            }
            options = $.extend(true, options, graph.data);

            //render graph
            var type = (!options.type ? 'line' : options.type.toLowerCase());
            //make sure that the function name is corrent
            var func = type.charAt(0).toUpperCase() + type.substr(1);

            Morris[func](options);
        }

        return this.each(function (options) {
            var settings = $.extend({
                width: '100%',
                height: '350px'
            }, options);

            try {
                //trim <!-- and -->
                var graphData = $(this).html().replace('<!--', '');
                graphData = graphData.replace('-->', '');

                renderGraph({
                    data: JSON.parse(graphData),
                    settings: settings,
                    element: $(this)
                });
            } catch (e) {
                console.log(e);
            }

        });
    };
}(jQuery));