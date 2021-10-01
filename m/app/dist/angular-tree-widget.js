/*
    @license Angular TreeWidget version 1.0.1
    ⓒ 2016 Alex Suleap https://github.com/AlexSuleap/agular-tree-widget
    License: MIT
*/

(function (angular) {
    'use strict';

    angular.module('TreeWidget', ['ngAnimate', 'RecursionHelper'])
        .directive('tree', function () {

            return {
                restrict: "E",
                scope: { nodes: '=', options: '=?' },
                template: "<treenode nodes='nodes' tree='nodelist' options='options'></treenode>",
                compile: function compile(tElement, tAttrs, transclude) {
                    return {
                        pre: function (scope) {
                            scope.nodelist = [];
                            scope.options = scope.options || (scope.options = { showIcon: true, expandOnClick: false, multipleSelect: false });
                            scope.count = 0;
                            function generateNodeList(nodes, parent) {
                                if (nodes != undefined) {
                                    if (nodes.length > 0) {
                                        for (var i = 0; i < nodes.length ; i++) {
                                            var node = nodes[i];

                                            //Generate node ids if no ids are defined
                                            if (node.nodeId === undefined) {
                                                node.nodeId = "tree-node-" + scope.count;
                                                scope.count++;
                                            }

                                            //expanded all the nodes
                                            if (node.expanded === undefined && node.children != undefined) {
                                                node.expanded = true;
                                            }
                                            if (parent != undefined) {
                                                node.parentId = parent.nodeId;
                                            }
                                            if (scope.nodelist.indexOf(node) == -1) {
                                                scope.nodelist.push(node);
                                            }
                                            generateNodeList(node.children, node);
                                        }
                                    }
                                }
                            }

                            scope.$watch(function () {
                                generateNodeList(scope.nodes);
                            });
                        }
                    }
                }
            }
        })
        .filter('nodeFilter', ['$filter', function ($filter) {

            return function (nodes, filter) {
                var recursiveFilter = function (nodes, filter) {
                    var filtered = [];
                    angular.forEach(nodes, function (node) {
                        if ($filter('filter')([node], filter).length > 0) {
                            filtered.push(node);
                        } else if (angular.isArray(node.children) && node.children.length > 0) {
                            var internal = recursiveFilter(node.children, filter);
                            if (internal.length > 0) {
                                filtered.push(node);
                            }
                        }
                    });
                    return filtered;
                };
                return recursiveFilter(nodes, filter);
            }

        }])
        .directive('treenode', ['RecursionHelper', function (RecursionHelper) {
            return {
                restrict: "E",
                scope: { nodes: '=', tree: '=', options: '=?' },

                template: '<ul tooltip="akash">'
                            + '<li ng-repeat="node in nodes | nodeFilter:options.filter" tooltip="node.name" class="node">'
                                + '<i tooltip="node.name" class="tree-node-ico pointer" ng-class="{\'tree-node-expanded\': !node.expanded && (node.children | nodeFilter:options.filter).length > 0,\'tree-node-collapsed\':node.expanded && (node.children | nodeFilter:options.filter).length > 0}" ng-click="toggleNode(node)"></i>'
                                + '<span tooltip="node.name" class="node-title pointer" ng-click="selectNode(node, $event)" ng-class="{\'disabled\':node.disabled}">'
                                    + '<span tooltip="node.name"><i class="tree-node-ico" ng-if="options.showIcon" ng-class="{\'tree-node-image\':node.children, \'tree-node-leaf\':!node.children}"  ng-style="node.image && {\'background-image\':\'url(\'+node.image+\')\'}"></i>'
                                    + '<span class="node-name dropdown" ng-class="{selected: node.selected&& !node.disabled}">'
        + '<label>{{node.name}}<input type="checkbox" /><ul class="left-click"><li><a ng-click="toggleModal22(\'success\',node.nodeId)">Select</a></li><li><a href="" ng-if="node.usetype==0 || node.usetype==1">Create Master</a></li><li><a href="" ng-if="node.usetype==0 || node.usetype==1">Create Dealer</a></li><li><a href="" ng-if="node.usetype==0 || node.usetype==1 || node.usetype==2">Create User</a></li><li><a href="">Add Acc.</a></li><li><a href="">View Acc.</a></li><li><a href="">Change Pwd.</a></li><hr/><li><a href="">Free Chips In/Out</a></li><li><a href="">Chips In/Out</a></li><hr/><li><a href="">Lock User</a></li><li><a href="">Lock Betting</a></li><li><a href="">Close Acc.</a></li></ul></label>'
                                    + '</span></span>'
                                + '</span>'
                                + '<treenode ng-if="node.children" nodes=\'node.children\' tree="tree" options="options" ng-hide="node.expanded" id="{{node.nodeId}}"></treenode>'
                            + '</li>'
                        + '</ul>',
                compile: function (element) {
                    return RecursionHelper.compile(element, function (scope, iElement, iAttrs, controller, transcludeFn) {
                        function cleanAllSelectedExcept(node) {
                            angular.forEach(scope.tree, function (item) {
                                if (node != item)
                                    item.selected = false;
                            });
                        }
                        scope.showModal1 = false;

                        scope.buttonClicked1 = "";
                        scope.toggleModal22 = function (btnClicked1, id) {

                            alert('Plaz' + btnClicked1);

                            scope.buttonClicked1 = btnClicked1;
                            alert('Plaz' + scope.buttonClicked1);

                            scope.showModal1 = !scope.showModal1;

                            scope.mid = id;
                            /*  $scope.fancyType=type;*/
                        };

                        function getSelectedNodes() {
                            return scope.tree.filter(function (item) { return item.selected; });
                        }

                        //Select node
                        scope.selectNode = function (node, $event) {
                            if (node.disabled) { return; }

                            var selectedNode;
                            if (scope.options.multipleSelect === true) {
                                node.selected = !node.selected;
                                selectedNode = getSelectedNodes();
                            } else if (scope.options.multipleSelect === 'ctrlKey' || scope.options.multipleSelect === 'altKey') {
                                if ($event[scope.options.multipleSelect]) {
                                    node.selected = !node.selected;
                                } else {
                                    node.selected = true;
                                    cleanAllSelectedExcept(node);
                                }
                                selectedNode = getSelectedNodes();
                            } else {
                                node.selected = true;
                                cleanAllSelectedExcept(node);
                                selectedNode = node;
                            }
                            scope.$emit('selection-changed', selectedNode);
                            if (scope.options.onSelectNode) {
                                scope.options.onSelectNode(selectedNode);
                            }

                            if (scope.options.expandOnClick) {
                                if (node.children != undefined) {
                                    node.expanded = !node.expanded;
                                    scope.$emit('expanded-state-changed', node);
                                    if (scope.options.onExpandNode) {
                                        scope.options.onExpandNode(node);
                                    }
                                }
                            }
                        }

                        //Expand collapse node
                        scope.toggleNode = function (node) {
                            if (node.children != undefined) {
                                node.expanded = !node.expanded;
                                scope.$emit('expanded-state-changed', node);
                                if (scope.options.onExpandNode) {
                                    scope.options.onExpandNode(node);
                                }
                            }
                        }
                    });
                }
            }
        }]);
       /* sourabh 5-oct .directive('modal1', function () {
           
            return {

              template: '<div class="modal fade">' + 
                  '<div class="modal-dialog">' + 
                    '<div class="modal-content">' + 
                      '<div class="modal-header">' + 
                        '<button type="button" class="close" data-dismiss="modal1" aria-hidden="true">&times;</button>' + 
                        '<h4 class="modal-title">Create NewUser </h4>' + 
                      '</div>' + 
                      '<div class="modal-body" ng-transclude></div>' + 
                    '</div>' + 
                  '</div>' + 
                '</div>',
              restrict: 'E',
              transclude: true,
              replace:true,
              scope:true,
              link: function postLink(scope, element, attrs) {
                  scope.$watch(attrs.visible, function(value){
                  if(value == true)
                    $(element).modal('show');
                  else
                    $(element).modal('hide');
                });

                $(element).on('shown.bs.modal1', function(){

                  scope.$apply(function(){
                    scope.$parent[attrs.visible] = true;
                  });
                });

                $(element).on('hidden.bs.modal1', function(){
                  scope.$apply(function(){
                    scope.$parent[attrs.visible] = false;
                  });
                });
              }
            };
          });*/

})(angular);
/*add span tooltip="{{node.name}}"*/
//sourabh 5-oct-2016
(function (f) { f.module("angularTreeview", []).directive("treeModel", function ($compile) { return { restrict: "A", link: function (b, h, c) { var a = c.treeId, g = c.treeModel, e = c.nodeLabel || "label", d = c.nodeChildren || "children", e = '<ul><li  data-ng-repeat="node in ' + g + '"><i class="collapsed" data-ng-show="node.' + d + '.length && node.collapsed" data-ng-click="' + a + '.selectNodeHead(node)"></i><i class="expanded" data-ng-show="node.' + d + '.length && !node.collapsed"  data-ng-click="' + a + '.selectNodeHead(node)"></i><i class="normal" data-ng-hide="node.' + d + '.length"></i> <span data-ng-class="node.selected" data-ng-click="' + a + '.selectNodeLabel(node)"  class="myMenu1"><span class="icon-span" style="background-image:url({{node.image}});padding: 0px 7px;background-repeat: no-repeat;background-size: 14px 16px;" tooltip="{{node.mstrname}}"></span> {{node.' + e + '}}</span><div data-ng-hide="node.collapsed" data-tree-id="' + a + '" data-tree-model="node.' + d + '" data-node-id=' + (c.nodeId || "id") + " data-node-label=" + e + "   data-node-children=" + d + "></div></li></ul>"; a && g && (c.angularTreeview && (b[a] = b[a] || {}, b[a].selectNodeHead = b[a].selectNodeHead || function (a) { a.collapsed = !a.collapsed }, b[a].selectNodeLabel = b[a].selectNodeLabel || function (c) { b[a].currentNode && b[a].currentNode.selected && (b[a].currentNode.selected = void 0); c.selected = "selected"; b[a].currentNode = c }), h.html('').append($compile(e)(b))) } } }) })(angular);

