var debounceTimer;

function debounce(func, wait) {
  let timeout;
  return function executedFunction(...args) {
    const later = () => {
      timeout = null;
      func(...args);
    };
    clearTimeout(timeout);
    timeout = setTimeout(later, wait);
  };
}

let rateLimiter = {
  counter: 0,
  timeout: null,
  maxAttempts: 3,
  timeWindow: 1000,

  reset() {
    this.counter = 0;
    clearTimeout(this.timeout);
    this.timeout = null;
  },

  increment() {
    this.counter++;
  
    if (this.counter >= this.maxAttempts) {
      console.log('spammer');
      this.timeWindow = 1000;
      clearTimeout(this.timeout);
      this.timeout = setTimeout(() => {
        this.reset();
      }, this.timeWindow);
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

class FoodList {
  constructor(containerId) {
    this.container = document.getElementById(containerId);
    this.step = 10;
    this.from = 0;
    this.to = this.step;
    this.lastSegment = this.getLastSegment();
    this.searchInput = document.getElementById('search_species_food');
    this.searchClear = document.getElementById('search_clear');
    this.loader = document.getElementById('food-list-loader');
    this.foodItems = [];
    this.init();
  }

  async fetchData(from, to, searchQuery = '', safeOnly = false) {
    try {
      // Show loader
      this.loader.style.display = 'block';
  
      const params = new URLSearchParams();
      params.append('from', from);
      params.append('to', to);
      params.append('q', searchQuery);
      if (safeOnly !== false) {
        params.append('safe_only', true);
      }
      params.append('locale', document.documentElement.lang); // Add locale parameter
      const queryString = params.toString();
      console.log(`http://localhost:8000/api/food_safety/${this.lastSegment}?${queryString}`);
      
      const response = await fetch(`/api/food_safety/${this.lastSegment}?${queryString}`);
      if (!response.ok) {
        throw new Error('Failed to fetch data');
      }
      const data = await response.json();
  
      // Hide loader after fetching data
      this.loader.style.display = 'none';
  
      return data;
    } catch (error) {
      console.error('Error fetching data:', error);
      throw error;
    }
  }
  
  getLastSegment() {
    const pathname = window.location.pathname;
    const segments = pathname.split('/');
    return segments[segments.length - 1];
  }

  renderFoodItems(data, append = false) {
    const fragment = document.createDocumentFragment();
  
    if (!rateLimiter.increment()) {
      const errorListItem = document.createElement('li');
      errorListItem.innerHTML = `
        <div>Too many requests.</div>
        <div class="error-timer"></div>
      `;
      fragment.appendChild(errorListItem);
  
      const errorTimerElement = errorListItem.querySelector('.error-timer');
      let remainingTime = 1000;
      const updateTimer = setInterval(() => {
        remainingTime -= 10;
        if (remainingTime <= 0) {
          clearInterval(updateTimer);
          rateLimiter.reset();
          // Check if there was an ongoing search or safe feed selection
          const searchValue = this.searchInput.value;
          const safeChecked = document.getElementById('safe_to_feed').checked;
          if (!this.searchInput.disabled && !safeChecked.disabled) {
            this.fetchData(this.from, this.to, searchValue, safeChecked)
              .then(data => this.renderFoodItems(data))
              .catch(error => console.error('Error fetching data:', error));
          }
        }
        errorTimerElement.textContent = (remainingTime / 1000).toFixed(2) + ' s';
      }, 10);
  
      // Clear previous results and replace with error message
      if (!append) {
        this.container.innerHTML = '';
      }
      this.container.appendChild(fragment);
    } else {
      // Proceed with rendering food items as usual
      if (!append) {
        this.foodItems = []; // Empty previous results when rendering new ones if append is false
        this.from = 0; // Reset pagination
        this.to = this.step;
      }
      data.data.foods.forEach(food => {
        const listItem = document.createElement('li');
        let htmlString = `<div>${food.food}</div>
                          <div class="item" style="background: ${food.hex_color}">
                            <span class="mr-3">${food.safety_label}</span>`;
        if (food.filename) {
          htmlString += `<img src="${food.filename}" height="40" alt="Icon">`;
        }
        // TODO
        // htmlString += `</div><a href="#">Read more <i class="fa-solid fa-arrow-up-right-from-square"></i></a>`;
        listItem.innerHTML = htmlString;
        fragment.appendChild(listItem);
        if (append) {
          this.foodItems.push(listItem); // Push new item to foodItems array only when appending
        }
      });
      
      if (data.data.foods.length < this.step || data.status === 404) {
        const endOfDataItem = document.createElement('li');
        if (data.status === 404) {
          endOfDataItem.innerHTML = '<div>No food data found for the specified parameters.</div>';
        } else {
          endOfDataItem.innerHTML = '<div>End of data.</div>';
        }
        fragment.appendChild(endOfDataItem);
        document.getElementById('load_more').style.display = 'none';
      }
  
      // Append food items
      this.container.appendChild(fragment);
    }
  }
  
  
  async loadMoreData() {
    this.from += this.step;
    this.to += this.step;
    try {
      const data = await this.fetchData(this.from, this.to, this.searchInput.value);
      this.renderFoodItems(data, true); // Pass true to indicate appending additional data
    } catch (error) {
      console.error('Error fetching data:', error);
    }
  }
  

  clearSearch() {
    this.searchInput.value = '';
    this.searchClear.style.visibility = 'hidden';
    this.foodItems = [];
    this.from = 0;
    this.to = this.step;
    this.container.innerHTML = '';
    this.init(); // Reinitialize the component after clearing the search
  }

  init() {
    const resetValues = () => {
      this.from = 0;
      this.to = this.step;
      this.foodItems = [];
      this.container.innerHTML = '';
      clearTimeout(debounceTimer);
    }
    const fetchDataDebounced = debounce(() => {
      resetValues();
      this.fetchData(this.from, this.to, this.searchInput.value)
        .then(data => this.renderFoodItems(data, false))
        .catch(error => console.error('Error fetching data:', error))
        .finally(() => {
          document.getElementById('safe_to_feed').disabled = false;
        });
    }, 1000);
  
    this.searchInput.addEventListener('input', () => {
      fetchDataDebounced();
      if (this.searchInput.value) {
        this.searchClear.style.visibility = 'visible';
      } else {
        this.searchClear.style.visibility = 'hidden';
        document.getElementById('load_more').style.display = 'block';
      }
    }, 1000);
  
    this.searchInput.addEventListener('keydown', (event) => {
      if (event.key === 'Enter') {
        if (this.searchInput.value.trim() === '') {
          return;
        }
        fetchDataDebounced();
      }
    });
    
  
    this.searchClear.addEventListener('click', () => {
      this.clearSearch();
    });
  
    document.getElementById('load_more').style.display = 'block';
  
    this.fetchData(this.from, this.to)
      .then(data => this.renderFoodItems(data))
      .catch(error => {
        if (error.response && error.response.status === 404) {
          console.error(`No data for phrase: ${this.searchInput.value}`);
        } else {
          console.error('Error fetching data:', error);
        }
      });
  
    document.getElementById('load_more').addEventListener('click', () => {
      this.loadMoreData();
    });
  
    const safeToFeedCheckbox = document.getElementById('safe_to_feed');
    safeToFeedCheckbox.addEventListener('change', () => {
      // Disable the checkbox while new data is being fetched
      safeToFeedCheckbox.disabled = true;
      resetValues();
      this.fetchData(this.from, this.to, this.searchInput.value, safeToFeedCheckbox.checked)
        .then(data => this.renderFoodItems(data))
        .catch(error => console.error('Error fetching data:', error))
        .finally(() => {
          // Re-enable the checkbox after rendering is complete
          safeToFeedCheckbox.disabled = false;
        });
    });
  }
  
  
}

const foodList = new FoodList('food_list_container');
