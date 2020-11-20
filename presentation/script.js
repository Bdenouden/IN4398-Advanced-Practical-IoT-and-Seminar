document.addEventListener('keydown', keyNav);

    function keyNav(e) {
        // console.log(e.code);
        var pageNumber = parseInt(GetURLParameter('page'));
        var max_pageNumber = document.getElementsByClassName('page').length;

        if (!pageNumber) {
            pageNumber = 0;
        }


        switch (e.code) {
            case 'ArrowLeft':
                if (pageNumber > 0) {
                    window.location.href = '?page=' + (pageNumber - 1);
                }
                break;

            case 'ArrowRight':
                if (pageNumber < max_pageNumber) {
                    window.location.href = '?page=' + (pageNumber + 1);
                }
                break;

            default:
                break;
        }

    }


    document.addEventListener("DOMContentLoaded", (event) => {
        console.log('DOM is ready.')
        var pageNumber = parseInt(GetURLParameter('page'));
        var max_pageNumber = document.getElementsByClassName('page').length;
        var page;
        if (!pageNumber || pageNumber > max_pageNumber) {
            page = document.getElementById('index');
            createIndex();
            document.getElementById("previousPage").style.display = 'none';
            document.getElementById("nextPage").href = '?page=1';
        } else {
            page = document.getElementById('page_' + pageNumber);
            document.getElementById("previousPage").href = pageNumber > 1 ? '?page=' + (pageNumber - 1) : 'presentation.html';

            if (pageNumber == max_pageNumber) {
                document.getElementById("nextPage").style.display = 'none';
            } else {
                document.getElementById("nextPage").href = '?page=' + (pageNumber + 1);
            }
        }

        page.style.display = "block";
    });

    function GetURLParameter(sParam) {
        var sPageURL = window.location.search.substring(1);
        var sURLVariables = sPageURL.split('&');
        for (var i = 0; i < sURLVariables.length; i++) {
            var sParameterName = sURLVariables[i].split('=');
            if (sParameterName[0] == sParam) {
                return sParameterName[1];
            }
        }
    }

    function createIndex() {
        const parent = document.getElementById("index");
        const pages = document.querySelectorAll(".page");
        let template = parent.getElementsByTagName("template")[0];

        var pageNumber, pageName, btn, clone, textfield;

        pages.forEach(page => {
            clone = template.content.cloneNode(true);
            btn = clone.querySelector("a");
            btnTxt = btn.getElementsByTagName('div')[0];
            console.log(page)

            pageNumber = page.id.split('_')[1];

            btnTxt.setAttribute("pageNumber", pageNumber);
            btnTxt.textContent = page.getAttribute("pageName") || "Page " + pageNumber;
            btn.href = "?page=" + pageNumber

            parent.appendChild(btn);
        });


    }