<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Task Manager</title>

        <link
            href="https://fonts.bunny.net/css2?family=Nunito:wght@400;600;700"
            rel="preload"
            as="style"
            onload="this.onload=null;this.rel='stylesheet';"
        />

        <style>
            html,body {
                margin: 0;
                padding: 0;
                height: 100%;
            }

            body {
                font-family: 'Nunito', sans-serif;
                background: url("{{ asset('img/bg1.webp') }}");
                background-size: cover;
                background-attachment: fixed;
            }

            #app {
                width: 100%;
                max-width: 320px;
                min-height: 300px;
                margin: auto;
                background: rgba(0, 32, 43, 0.9);
            }

            @media (min-width: 800px) {
                #app {
                    max-width: 500px;
                }
            }

            .app-title {
                font-size: 22px;
                color: #FFFFFF;
                text-align: center;
                padding: 10px;
                border-bottom: 1px solid #1f3942;
            }

            .list {

            }

            .list .list-item {
                padding: 14px 0 14px 0;
                border-bottom: 1px dashed rgba(70, 107, 119, 0.44);
                position: relative;
                min-height: 82px;
            }


            .list .list-item .time-interaction {
                height: 20px;
                width: 30px;
                position: absolute;
                left: 5px;
                text-align: center;
            }

            .list .list-item .time-interaction .play {
                width: 0;
                height: 0;
                border-style: solid;
                border-width: 10px 0 10px 17.3px;
                border-color: transparent transparent transparent #ffffff;
                display: inline-block;
                cursor: pointer;
            }

            .list .list-item .time-interaction .pause {
                width: 100%;
                height: 100%;
                text-align: center;
                display: inline-block;
                cursor: pointer;
            }

            .list .list-item .time-interaction .pause .pause-col {
                height: 100%;
                width: 4px;
                background: #FFFFFF;
                display: inline-block;
            }

            .list .list-item .time-interaction .pause .pause-col:first-child {
                margin-right: 5px;
            }

            .list .list-item .list-item-title {
                color: #FFFFFF;
                font-size: 14px;
                padding-left: 40px;
                padding-right: 30px;

                position: relative;
            }

            .list .list-item .list-item-title svg {
                position: absolute;
                left: 15px;
                top: 2px;
            }

            .list .list-item .list-item-options {
                position: absolute;
                right: 0;
                height: 30px;
                width: 30px;
                cursor: pointer;
            }


            .list .list-item .list-item-options .option-dot {
                height: 8px;
                width: 8px;
                background: #FFFFFF;
                -webkit-border-radius: 8px;
                -moz-border-radius: 8px;
                border-radius: 8px;
                margin: auto;
                margin-bottom: 2px;
            }

            .list .list-item .time-spent {
                color: rgba(255, 198, 2, 0.76);
                text-align: left;
                font-size: 12px;
                margin-top: 10px;

                padding-left: 50px;
                padding-right: 30px;
            }

            .list .list-item .folder {
                color: #7a9fa4;
                text-align: left;
                font-size: 12px;
                margin-top: 10px;
                padding-left: 40px;
                padding-right: 30px;
            }

            .list .list-item .folder .breadcrumb {
                margin-left: 6px;
                margin-right: 6px;
                color: #FFFFFF;
                font-size: 12px;
            }

            .list .list-item .tags {
                text-align: right;
                margin-top: 10px;

                padding-left: 40px;
                padding-right: 30px;
            }

            .list .list-item .tags span {
                background: #FFFFFF;
                margin-right: 10px;
                font-size: 10px;
                padding: 4px 10px;

                -webkit-border-radius: 8px;
                -moz-border-radius: 8px;
                border-radius: 8px;

                cursor: pointer;

                display: inline-block;
                margin-top: 8px;
            }



        </style>

        <script>
            window.App = {
                Helpers: {
                    getVerticalCenter: function (elementHeight, containerHeight) {
                        return (containerHeight/2)-(elementHeight/2);
                    }
                },
                Components: {
                    FolderContentList: {
                        el: function () {
                            return document.getElementById('folder-content-list');
                        },
                        addListItem: function (listItemObj) {
                            const newListItem = this.Components.ListItem.createEl(listItemObj);
                            this.el().appendChild(newListItem.listItemEl);

                            // center time interaction vertically
                            if ( newListItem.timeInteractionEl.style.display !== 'none' ) {
                                const timeInteractionYPos = window.App.Helpers.getVerticalCenter(
                                    newListItem.timeInteractionEl.offsetHeight,
                                    newListItem.listItemEl.offsetHeight
                                );
                                newListItem.timeInteractionEl.style.top = timeInteractionYPos + 'px';
                            }

                            // center list item options 3 dots btn vertically
                            const listItemOptionsBtnYPos = window.App.Helpers.getVerticalCenter(
                                newListItem.listItemOptionsEl.offsetHeight,
                                newListItem.listItemEl.offsetHeight
                            );
                            newListItem.listItemOptionsEl.style.top = listItemOptionsBtnYPos + 'px';
                        },
                        Components: {
                            ListItem: {
                                createEl: function (listItemObj) {
                                    const listItem = document.createElement('div');
                                    listItem.classList.add('list-item');

                                    const timeInteraction = this.createTimeInteractionButtonEl(listItemObj);
                                    const listItemOptions = this.createListItemOptionsEl(listItemObj);
                                    const listItemTitle = this.createListItemTitleEl(listItemObj);
                                    const timeSpent = this.createTimeSpentEl(listItemObj);
                                    const parentFolders = this.createParentFoldersEl(listItemObj);
                                    const tags = this.createTagsEl(listItemObj);

                                    listItem.appendChild(timeInteraction);
                                    listItem.appendChild(listItemOptions);
                                    listItem.appendChild(listItemTitle);
                                    listItem.appendChild(timeSpent);
                                    listItem.appendChild(parentFolders);
                                    listItem.appendChild(tags);

                                    return {
                                        listItemEl: listItem,
                                        timeInteractionEl: timeInteraction,
                                        listItemTitleEl: listItemTitle,
                                        listItemOptionsEl: listItemOptions
                                    };
                                },
                                createTimeInteractionButtonEl: function (listItemObj) {
                                    const timeInteraction = document.createElement('div');
                                    timeInteraction.classList.add('time-interaction');

                                    if (listItemObj.list_item_type === 'task') {
                                        const playButton = this.createPlayButtonEl(listItemObj);
                                        const pauseButton = this.createPauseButtonEl(listItemObj);

                                        timeInteraction.appendChild(playButton);
                                        timeInteraction.appendChild(pauseButton);
                                    } else {
                                        timeInteraction.style.display = 'none';
                                    }

                                    return timeInteraction;
                                },
                                createPlayButtonEl: function (listItemObj) {
                                    const playButton = document.createElement('div');
                                    playButton.classList.add('play');
                                    //playButton.style.display = 'none';
                                    return playButton;
                                },
                                createPauseButtonEl: function (listItemObj) {
                                    const pauseButton = document.createElement('div');
                                    pauseButton.classList.add('pause');
                                    pauseButton.style.display = 'none';

                                    const pauseCol1 = document.createElement('div');
                                    pauseCol1.classList.add('pause-col');

                                    const pauseCol2 = document.createElement('div');
                                    pauseCol2.classList.add('pause-col');

                                    pauseButton.appendChild(pauseCol1);
                                    pauseButton.appendChild(pauseCol2);

                                    return pauseButton;
                                },
                                createListItemOptionsEl: function (listItemObj) {
                                    const listItemOptions = document.createElement('div');
                                    listItemOptions.classList.add('list-item-options');

                                    const optionDot1 = document.createElement('div');
                                    optionDot1.classList.add('option-dot');

                                    const optionDot2 = document.createElement('div');
                                    optionDot2.classList.add('option-dot');

                                    const optionDot3 = document.createElement('div');
                                    optionDot3.classList.add('option-dot');

                                    listItemOptions.appendChild(optionDot1);
                                    listItemOptions.appendChild(optionDot2);
                                    listItemOptions.appendChild(optionDot3);

                                    return listItemOptions;
                                },
                                createListItemTitleEl: function (listItemObj) {
                                    const listItemTitle = document.createElement('div');
                                    listItemTitle.classList.add('list-item-title');
                                    listItemTitle.innerHTML = '';

                                    const listItemTitleText = listItemObj.list_item_type === 'task' ? listItemObj.title : listItemObj.name;
                                    const listItemTitleTextEl = document.createElement('span');
                                    listItemTitleTextEl.innerText = listItemTitleText;

                                    switch (listItemObj.list_item_type) {
                                        case 'task':
                                            listItemTitle.innerHTML += '<svg style="color: white" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-activity" viewBox="0 0 16 16"> <path fill-rule="evenodd" d="M6 2a.5.5 0 0 1 .47.33L10 12.036l1.53-4.208A.5.5 0 0 1 12 7.5h3.5a.5.5 0 0 1 0 1h-3.15l-1.88 5.17a.5.5 0 0 1-.94 0L6 3.964 4.47 8.171A.5.5 0 0 1 4 8.5H.5a.5.5 0 0 1 0-1h3.15l1.88-5.17A.5.5 0 0 1 6 2Z" fill="white"></path> </svg>';
                                            break;
                                        case 'folder':
                                            listItemTitle.innerHTML += '<svg style="color: white" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-folder" viewBox="0 0 16 16"> <path d="M.54 3.87.5 3a2 2 0 0 1 2-2h3.672a2 2 0 0 1 1.414.586l.828.828A2 2 0 0 0 9.828 3h3.982a2 2 0 0 1 1.992 2.181l-.637 7A2 2 0 0 1 13.174 14H2.826a2 2 0 0 1-1.991-1.819l-.637-7a1.99 1.99 0 0 1 .342-1.31zM2.19 4a1 1 0 0 0-.996 1.09l.637 7a1 1 0 0 0 .995.91h10.348a1 1 0 0 0 .995-.91l.637-7A1 1 0 0 0 13.81 4H2.19zm4.69-1.707A1 1 0 0 0 6.172 2H2.5a1 1 0 0 0-1 .981l.006.139C1.72 3.042 1.95 3 2.19 3h5.396l-.707-.707z" fill="white"></path> </svg>';
                                            break;
                                    }

                                    listItemTitle.appendChild(listItemTitleTextEl);
                                    return listItemTitle;
                                },
                                createTimeSpentEl: function (listItemObj) {
                                    const timeSpent = document.createElement('div');
                                    timeSpent.classList.add('time-spent');

                                    if (
                                        typeof listItemObj.time_spent_today !== 'undefined'
                                    ) {
                                        timeSpent.innerHTML = 'time spent on this task today: <b>' + listItemObj.time_spent_today + '</b>';
                                    } else {
                                        timeSpent.style.display = 'none';
                                    }

                                    return timeSpent;
                                },
                                createParentFoldersEl: function (listItemObj) {
                                    const parentFolders = document.createElement('div');
                                    parentFolders.classList.add('folder');

                                    for(var i = 0; i < listItemObj.parent_folders.length; i++) {
                                        const parentFolder = listItemObj.parent_folders[i];
                                        const parentFolderName = parentFolder.name;

                                        const parentFolderEl = document.createElement('span');
                                        parentFolderEl.innerText = parentFolderName;

                                        parentFolders.appendChild(parentFolderEl);

                                        if (
                                            (i+1) < listItemObj.parent_folders.length
                                        ) {
                                            const breadcrumb = document.createElement('span');
                                            breadcrumb.classList.add('breadcrumb');
                                            breadcrumb.innerText = '/';
                                            parentFolders.appendChild(breadcrumb);
                                        }
                                    }

                                    return parentFolders;
                                },
                                createTagsEl: function (listItemObj) {
                                    const tags = document.createElement('div');
                                    tags.classList.add('tags');

                                    for(var i = 0; i < listItemObj.tags.length; i++) {
                                        const tag = listItemObj.tags[i];
                                        const tagName = tag.name;
                                        const tagEl = document.createElement('span');
                                        tagEl.innerText = tagName;

                                        tags.appendChild(tagEl);
                                    }

                                    return tags;
                                }
                            }
                        },
                    },
                },
                Views: {
                    FolderContent: {
                        api: "{{ url('/api/folder-content/list') }}",
                        show: function () {
                            this.fetchFolderContent();
                        },
                        fetchFolderContent: function () {
                            var xhr = new XMLHttpRequest();
                            xhr.withCredentials = true;

                            xhr.addEventListener("readystatechange", function() {
                                if(this.readyState === 4) {
                                    try {
                                        const folderContentJson = JSON.parse(this.responseText);

                                        // add tasks first
                                        for(var i = 0; i < folderContentJson.tasks.length; i++) {
                                            const listItem = folderContentJson.tasks[i];
                                            listItem.list_item_type = 'task';
                                            window.App.Components.FolderContentList.addListItem(
                                                listItem
                                            );
                                        }


                                        // add folders last
                                        for(var i = 0; i < folderContentJson.folders.length; i++) {
                                            const listItem = folderContentJson.folders[i];
                                            listItem.list_item_type = 'folder';
                                            window.App.Components.FolderContentList.addListItem(
                                                listItem
                                            );
                                        }

                                    } catch (error) {
                                        // notify could not fetch/list folder content ( unexpected invalid json response )
                                    }
                                }
                            });

                            xhr.open("POST", this.api);

                            xhr.send();
                        }
                    }
                }
            };
        </script>
    </head>
    <body>
        <div id="app">
            <div class="app-title">Task Manager</div>

            <div class="list" id="folder-content-list"></div>
        </div>


        @if(isset($view))
            <script>
                window.App.currentView = '{{ $view }}';
            </script>
        @endif


        <script>
            if (
                typeof window.App.currentView !== 'undefined'
                &&
                typeof window.App.Views[window.App.currentView] !== 'undefined'
                &&
                typeof window.App.Views[window.App.currentView].show === 'function'
            ) {
                window.App.Views[window.App.currentView].show();
            }
        </script>

        <script>

            /**
             * Rotating background functionality
             */

            const backgroundList = [
                "{{ asset('img/bg1.webp') }}",
                "{{ asset('img/bg2.webp') }}",
            ];

            function setRandomBgImage() {
                const randBg = Math.floor(Math.random() * backgroundList.length);
                document.body.style.backgroundImage = "url('" + backgroundList[randBg] +"')";
            }

            setRandomBgImage();

            setInterval(function() {
                setRandomBgImage();
            }, 60*1000);

            /**
             * -----------------------------------
             */
        </script>
    </body>
</html>
