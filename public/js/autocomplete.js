var debounceTimer;

// Function to load search history
function load_search_history() {
    var search_query = document.getElementsByName('search_box')[0].value;
    if (search_query == '') {
        // Show loader when history is being loaded
        document.getElementById('search-loader').style.display = 'block';

        fetch("/api/autocomplete?q=" + search_query + "&locale=" + document.documentElement.lang, {
            method: "GET",
            headers: {
                'Content-type': 'application/json; charset=UTF-8'
            }
        }).then(function(response) {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        }).then(function(responseData) {
            // Hide loader when history loading is complete
            document.getElementById('search-loader').style.display = 'none';

            var data = responseData.data;

            if (data.length > 0) {
                var html = '<ul class="list-group">';
                html += '<li class="list-group-item-history d-flex justify-content-between align-items-center"><b class="text-primary"><i>' + t.your_recent_searches + '</i></b></li>';
                for (var count = 0; count < data.length; count++) {
                    html += '<li class="list-group-item-history text-muted" style="cursor:pointer">';
                    html += '<i class="fas fa-history mr-3"></i>';
                    html += '<span onclick="get_text(this)">' + data[count].search_query + '</span>';
                    html += '<i class="far fa-trash-alt float-right mt-1" onclick="delete_search_history(' + data[count].id + ')"></i>';
                    html += '</li>';
                }
                html += '</ul>';
                document.getElementById('search_result').innerHTML = html;
            }
        }).catch(function(error) {
            // Hide loader and show error message
            document.getElementById('search-loader').style.display = 'none';
            document.getElementById('search_result').innerHTML = '<p>' + t.something_went_wrong + '</p>';
            console.error('Error:', error);
        });
    }
}

// Function to load data
function load_data(query) {
    if (query.length > 2) {
        // Show loader when data is being loaded
        document.getElementById('search-loader').style.display = 'block';

        fetch("/api/autocomplete?q=" + query + "&locale=" + document.documentElement.lang, {
            method: "GET",
            headers: {
                'Content-type': 'application/json; charset=UTF-8'
            }
        }).then(function(response) {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        }).then(function(response) {
            // Hide loader when data loading is complete
            document.getElementById('search-loader').style.display = 'none';

            var data = response.data;

            var html = '<div class="list-group">';
            if (data.length > 0) {
                for (var count = 0; count < data.length; count++) {
                    html += '<a href="' + response.locale + '/species/' + data[count].gbif_id + '" class="list-group-item list-group-item-action" onclick="get_text(this)">';
                    html += data[count].name;
                    if (data[count].category) {
                        html += '<div class="category">';
                        html += '<img src="/assets/icons/' + data[count].category + '.png" class="icon">';
                        html += data[count].category;
                        html += '</div>';
                    }
                    html += '</a>';
                }
                
            } else {
                html += '<a href="#" class="list-group-item list-group-item-action disabled">' + t.no_data_found + '</a>';
            }
            html += '</div>';
            document.getElementById('search_result').innerHTML = html;
        }).catch(function(error) {
            // Hide loader and show error message
            document.getElementById('search-loader').style.display = 'none';
            document.getElementById('search_result').innerHTML = '<p>' + t.something_went_wrong + '</p>';
            console.error('Error:', error);
        });
    } else {
        document.getElementById('search_result').innerHTML = '';
    }
}

// Function to debounce execution
function debounce(func, wait) {
    clearTimeout(debounceTimer);
    debounceTimer = setTimeout(func, wait);
}
// Object for rate limiting
let rateLimiter = {
    counter: 0,
    timeout: null,
    maxAttempts: 3,
    timeWindow: 1000, // 1 second

    // Reset the counter and timeout
    reset() {
        this.counter = 0;
        clearTimeout(this.timeout);
        this.timeout = null;
    },

    increment() {
        this.counter++;

        if (this.counter >= this.maxAttempts) {
            // Display error message with timer
            const errorElement = document.getElementById('search_result');
            const errorMessage = '<div class="list-group"><a href="#" class="list-group-item list-group-item-action disabled">' + t.too_many_requests + '<span id="error-timer" class="ml-2">1.00 s</span></a></div>';
            errorElement.innerHTML = errorMessage;
        
            // Update the timer every 10 milliseconds
            const errorTimerElement = document.getElementById('error-timer');
            let remainingTime = 1000;
            const updateTimer = setInterval(() => {
                remainingTime -= 10;
                if (remainingTime <= 0) {
                    clearInterval(updateTimer);
                    rateLimiter.reset();
                }
                errorTimerElement.textContent = (remainingTime / 1000).toFixed(2) + ' s';
            }, 10);
        
            return false;
        }
        

        if (!this.timeout) {
            this.timeout = setTimeout(() => {
                this.reset();
            }, this.timeWindow);
        }

        return true;
    }
};

// Event listener for keyup event on search box
document.getElementById('search_box').addEventListener('keyup', function(event) {
    // TODO remake to get 10 most searched results, then won't need limit
    if (document.getElementById('search_box').value.length < 3) {
        document.getElementById('search_result').innerHTML = '<div class="list-group"><a href="#" class="list-group-item list-group-item-action disabled">' + t.min_characters + '</a></div>';
        return;
    }
    if (event.key === 'Enter') {
        if (!rateLimiter.increment()) {
            return;
        }
        // Debounce the fetch function for Enter key presses
        debounce(function() {
            load_data(document.getElementById('search_box').value);
        }, 1000);
    } else {
        // For other key presses, debounce without rate limiting
        debounce(function() {
            load_data(document.getElementById('search_box').value);
        }, 1000);
    }
});

document.getElementById('search_box').addEventListener('focus', function() {
    debounce(load_search_history, 1000);
});