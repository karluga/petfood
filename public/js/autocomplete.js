function load_search_history() {
    var search_query = document.getElementsByName('search_box')[0].value;
    if (search_query == '') {
        // Show loader when history is being loaded
        document.getElementById('search-loader').style.display = 'block';

        fetch("api/autocomplete", {
            method: "POST",
            body: JSON.stringify({
                action: 'fetch'
            }),
            headers: {
                'Content-type': 'application/json; charset=UTF-8'
            }
        }).then(function(response) {
            return response.json();
        }).then(function(responseData) {
            // Hide loader when history loading is complete
            document.getElementById('search-loader').style.display = 'none';

            if (responseData.length > 0) {
                var html = '<ul class="list-group">';
                html += '<li class="list-group-item d-flex justify-content-between align-items-center"><b class="text-primary"><i>Your Recent Searches</i></b></li>';
                for (var count = 0; count < responseData.length; count++) {
                    html += '<li class="list-group-item text-muted" style="cursor:pointer">';
                    html += '<i class="fas fa-history mr-3"></i>';
                    html += '<span onclick="get_text(this)">' + responseData[count].search_query + '</span>';
                    html += '<i class="far fa-trash-alt float-right mt-1" onclick="delete_search_history(' + responseData[count].id + ')"></i>';
                    html += '</li>';
                }                
                html += '</ul>';
                document.getElementById('search_result').innerHTML = html;
            }
        });
    }
}

function load_data(query) {
    if (query.length > 2) {
        // Show loader when data is being loaded
        document.getElementById('search-loader').style.display = 'block';

        var form_data = new FormData();
        form_data.append('query', query);
        var ajax_request = new XMLHttpRequest();
        ajax_request.open('POST', 'api/autocomplete');
        ajax_request.send(form_data);
        ajax_request.onreadystatechange = function() {
            if (ajax_request.readyState == 4 && ajax_request.status == 200) {
                // Hide loader when data loading is complete
                document.getElementById('search-loader').style.display = 'none';

                var response = JSON.parse(ajax_request.responseText);
                var html = '<div class="list-group">';
                if (response.length > 0) {
                    for (var count = 0; count < response.length; count++) {
                        html += '<a href="#" class="list-group-item list-group-item-action" onclick="get_text(this)">' + response[count].canonicalName + '</a>';
                    }
                } else {
                    html += '<a href="#" class="list-group-item list-group-item-action disabled">No Data Found</a>';
                }
                html += '</div>';
                document.getElementById('search_result').innerHTML = html;
            }
        }
    } else {
        document.getElementById('search_result').innerHTML = '';
    }
}
