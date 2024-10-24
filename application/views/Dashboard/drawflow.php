<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>Drawflow</title>
    </head>
    <body>
        <script src="<?php echo base_url(); ?>assets/drawflow/drawflow.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.0/js/all.min.js" integrity="sha256-KzZiKy0DWYsnwMF+X1DvQngQ2/FxF7MF3Ff72XcpuPs=" crossorigin="anonymous"></script>
        <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/drawflow/drawflow.css" />
        <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/drawflow/beautiful.css" />
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.0/css/all.min.css" integrity="sha256-h20CPZ0QyXlBuAw7A+KluUYx/3pK+c7lYEpqLTlxjYQ=" crossorigin="anonymous"/>
        <link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet">
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>
        <script src="https://unpkg.com/micromodal/dist/micromodal.min.js"></script>
        <script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>
        <script>
            var base_url = '<?php echo base_url(); ?>'
        </script>
        <header>
            <h2>Drawflow</h2>
            <div class="github-link"><a href="https://github.com/jerosoler/Drawflow" target="_blank"><i class="fab fa-github fa-3x"></i></a></div>
        </header>
        <div class="wrapper">
            <div class="col">
                <div class="drag-drawflow" draggable="true" ondragstart="drag(event)" data-node="text">
                    <i class="fab fa-facebook"></i><span> Text</span>
                </div>
                <div class="drag-drawflow" draggable="true" ondragstart="drag(event)" data-node="btn_url">
                    <i class="fab fa-slack"></i><span> Media</span>
                </div>
                <div class="drag-drawflow" draggable="true" ondragstart="drag(event)" data-node="templates">
                    <i class="fas fa-code"></i><span> Template</span>
                </div>
                <div class="drag-drawflow" draggable="true" ondragstart="drag(event)" data-node="database">
                    <i class="fas fa-mouse"></i><span> Database!</span>
                </div>
            </div>
            <div class="col-right">
                <div class="menu">
                    <ul>
                        <li onclick="editor.changeModule('Home'); changeModule(event);" class="selected">Home</li>
                    </ul>
                </div>
                <div id="drawflow" ondrop="drop(event)" ondragover="allowDrop(event)">

                    <!--<div class="btn-export" onclick="Swal.fire({title: 'Export', html: '<pre><code>' + JSON.stringify(editor.export(), null, 4) + '</code></pre>'})">Export</div>-->
                    <div class="btn-export" onclick="exportData()">Export</div>
                    <div class="btn-clear" onclick="editor.clearModuleSelected()">Clear</div>
                    <div class="btn-lock">
                        <i id="lock" class="fas fa-lock" onclick="editor.editor_mode = 'fixed'; changeMode('lock');"></i>
                        <i id="unlock" class="fas fa-lock-open" onclick="editor.editor_mode = 'edit'; changeMode('unlock');" style="display:none;"></i>
                    </div>
                    <div class="bar-zoom">
                        <i class="fas fa-search-minus" onclick="editor.zoom_out()"></i>
                        <i class="fas fa-search" onclick="editor.zoom_reset()"></i>
                        <i class="fas fa-search-plus" onclick="editor.zoom_in()"></i>
                    </div>
                </div>
            </div>
        </div>

        <script>

            var id = document.getElementById("drawflow");
            const editor = new Drawflow(id);
            editor.reroute = true;
            editor.reroute_fix_curvature = true;
            editor.force_first_input = false;
            editor.start();


            //editor.addNode(name, "typenode": false,  inputs, outputs, posx, posy, class, data, html);
            /*editor.addNode('welcome', 0, 0, 50, 50, 'welcome', {}, welcome );
             editor.addModule('Other');
             */

            // Events!
            editor.on('nodeCreated', function (id) {
                console.log("Node created " + id);
            })

            editor.on('nodeRemoved', function (id) {
                console.log("Node removed " + id);
            })

            editor.on('nodeSelected', function (id) {
                console.log("Node selected " + id);
            })

            editor.on('moduleCreated', function (name) {
                console.log("Module Created " + name);
            })

            editor.on('moduleChanged', function (name) {
                console.log("Module Changed " + name);
            })

            editor.on('connectionCreated', function (connection) {
                console.log('Connection created');
                console.log(connection);
            })

            editor.on('connectionRemoved', function (connection) {
                console.log('Connection removed');
                console.log(connection);
            })
            /*
             editor.on('mouseMove', function(position) {
             console.log('Position mouse x:' + position.x + ' y:'+ position.y);
             })
             */
            editor.on('nodeMoved', function (id) {
                console.log("Node moved " + id);
            })

            editor.on('zoom', function (zoom) {
                console.log('Zoom level ' + zoom);
            })

            editor.on('translate', function (position) {
                console.log('Translate x:' + position.x + ' y:' + position.y);
            })

            editor.on('addReroute', function (id) {
                console.log("Reroute added " + id);
            })

            editor.on('removeReroute', function (id) {
                console.log("Reroute removed " + id);
            })
            /* DRAG EVENT */

            /* Mouse and Touch Actions */

            var elements = document.getElementsByClassName('drag-drawflow');
            for (var i = 0; i < elements.length; i++) {
                elements[i].addEventListener('touchend', drop, false);
                elements[i].addEventListener('touchmove', positionMobile, false);
                elements[i].addEventListener('touchstart', drag, false);
            }

            var mobile_item_selec = '';
            var mobile_last_move = null;
            function positionMobile(ev) {
                mobile_last_move = ev;
            }

            function allowDrop(ev) {
                ev.preventDefault();
            }

            function drag(ev) {
                if (ev.type === "touchstart") {
                    mobile_item_selec = ev.target.closest(".drag-drawflow").getAttribute('data-node');
                } else {
                    ev.dataTransfer.setData("node", ev.target.getAttribute('data-node'));
                }
            }

            function drop(ev) {
                if (ev.type === "touchend") {
                    var parentdrawflow = document.elementFromPoint(mobile_last_move.touches[0].clientX, mobile_last_move.touches[0].clientY).closest("#drawflow");
                    if (parentdrawflow != null) {
                        addNodeToDrawFlow(mobile_item_selec, mobile_last_move.touches[0].clientX, mobile_last_move.touches[0].clientY);
                    }
                    mobile_item_selec = '';
                } else {
                    ev.preventDefault();
                    var data = ev.dataTransfer.getData("node");
                    addNodeToDrawFlow(data, ev.clientX, ev.clientY);
                }

            }

            function addNodeToDrawFlow(name, pos_x, pos_y) {
                if (editor.editor_mode === 'fixed') {
                    return false;
                }
                pos_x = pos_x * (editor.precanvas.clientWidth / (editor.precanvas.clientWidth * editor.zoom)) - (editor.precanvas.getBoundingClientRect().x * (editor.precanvas.clientWidth / (editor.precanvas.clientWidth * editor.zoom)));
                pos_y = pos_y * (editor.precanvas.clientHeight / (editor.precanvas.clientHeight * editor.zoom)) - (editor.precanvas.getBoundingClientRect().y * (editor.precanvas.clientHeight / (editor.precanvas.clientHeight * editor.zoom)));

                switch (name) {
                    case 'text':
                        var text = '<div><div class="title-box"> Enter Text Message</div>' +
                                '<div class="box">' +
                                '<input type="text" df-name>' +
                                '</div>' +
                                '</div>';
                        editor.addNode('Text', 1, 1, pos_x, pos_y, 'text', {}, text);
                        break;
                    case 'btn_url':
                        var btn_url_template = '<div>' +
                                '<div class="title-box">Send Media</div>' +
                                '<div class="box">' +
                                '<p>Enter repository url</p>' +
                                '<input type="text" df-name>' +
                                '</div>' +
                                '</div>';
                        editor.addNode('Send Media', 1, 1, pos_x, pos_y, 'btn_url', {"name": ''}, btn_url_template);
                        break;
                    case 'templates':
                        var templatesbot = '<div>' +
                                '<div class="title-box"><i class="fab fa-telegram-plane"></i> Telegram bot</div>' +
                                '<div class="box">' +
                                '<p>select template</p>' +
                                '<select df-channel>' +
                                '<option value="template_1">Template 1</option>' +
                                '<option value="template_2">Template 2</option>' +
                                '<option value="template_3">Template 3</option>' +
                                '<option value="template_4">Template 4</option>' +
                                '</select>' +
                                '</div>' +
                                '</div>';
                        editor.addNode('templates', 1, 1, pos_x, pos_y, 'templates', {"channel": 'channel_3'}, templatesbot);
                        break;
                    case 'database':
                        var db = '<div>' +
                                '<div class="title-box"><i class="fab fa-aws"></i> DB Save </div>' +
                                '<div class="box">' +
                                '<p>Save in db</p>' +
                                '<input type="text" df-db-dbname placeholder="DB name"><br><br>' +
                                '<input type="text" df-db-key placeholder="DB key">' +
                                '<p>Output Log</p>' +
                                '</div>' +
                                '</div>';
                        editor.addNode('database', 1, 1, pos_x, pos_y, 'database', {"db": {"dbname": '', "key": ''}}, db);
                        break;
                    default:
                }
            }

            var transform = '';
            function showpopup(e) {
                e.target.closest(".drawflow-node").style.zIndex = "9999";
                e.target.children[0].style.display = "block";
                //document.getElementById("modalfix").style.display = "block";

                //e.target.children[0].style.transform = 'translate('+translate.x+'px, '+translate.y+'px)';
                transform = editor.precanvas.style.transform;
                editor.precanvas.style.transform = '';
                editor.precanvas.style.left = editor.canvas_x + 'px';
                editor.precanvas.style.top = editor.canvas_y + 'px';
                console.log(transform);

                //e.target.children[0].style.top  =  -editor.canvas_y - editor.container.offsetTop +'px';
                //e.target.children[0].style.left  =  -editor.canvas_x  - editor.container.offsetLeft +'px';
                editor.editor_mode = "fixed";

            }

            function closemodal(e) {
                e.target.closest(".drawflow-node").style.zIndex = "2";
                e.target.parentElement.parentElement.style.display = "none";
                //document.getElementById("modalfix").style.display = "none";
                editor.precanvas.style.transform = transform;
                editor.precanvas.style.left = '0px';
                editor.precanvas.style.top = '0px';
                editor.editor_mode = "edit";
            }

            function changeModule(event) {
                var all = document.querySelectorAll(".menu ul li");
                for (var i = 0; i < all.length; i++) {
                    all[i].classList.remove('selected');
                }
                event.target.classList.add('selected');
            }

            function changeMode(option) {

                //console.log(lock.id);
                if (option == 'lock') {
                    lock.style.display = 'none';
                    unlock.style.display = 'block';
                } else {
                    lock.style.display = 'block';
                    unlock.style.display = 'none';
                }

            }

            function exportData() {
                var data = editor.export();
                $.ajax({
                    type: "POST",
                    url: base_url + "dashboard/save_drawflow_data",
                    data: data,
                    success: function (result) {
                        console.log(result);
                    }
                });
            }

        </script>
    </body>
</html>
