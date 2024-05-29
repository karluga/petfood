var debounceTimer;

// Function to debounce execution
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
      console.log('spammer');
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
    this.loadMoreData = this.loadMoreData.bind(this);
    this.clearSearch = this.clearSearch.bind(this);
    this.foodItems = [];
    this.init();
  }

  async fetchData(from, to, searchQuery = '') {
    try {
      const response = await fetch(`/api/food_safety/${this.lastSegment}?from=${from}&to=${to}&q=${searchQuery}`);
      if (!response.ok) {
        throw new Error('Failed to fetch data');
      }
      const data = await response.json();
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

  renderFoodItems(data) {
    const fragment = document.createDocumentFragment();

    // Check if rate limiting is active and render error message
    if (!rateLimiter.increment()) {
      const errorListItem = document.createElement('li');
      errorListItem.innerHTML = `
        <div>Too many requests.</div>
        <div class="error-timer"></div>
      `;
      fragment.appendChild(errorListItem);

      // Update the timer every 10 milliseconds
      const errorTimerElement = errorListItem.querySelector('.error-timer');
      let remainingTime = 1000;
      const updateTimer = setInterval(() => {
        remainingTime -= 10;
        if (remainingTime <= 0) {
          clearInterval(updateTimer);
          rateLimiter.reset();
        }
        errorTimerElement.textContent = (remainingTime / 1000).toFixed(2) + ' s';
      }, 10);
    } else {
      data.data.foods.forEach(food => {
        const listItem = document.createElement('li');
        listItem.innerHTML = `<div>${food.food}</div>
                              <div class="item" style="background: ${food.hex_color}">
                                <span class="mr-3">${food.safety_label}</span>`
        if (food.filename) {
          listItem.innerHTML += `<img src="${food.filename}" height="40" alt="Icon">`;
        }         
        listItem.innerHTML += `</div>
                              <a href="#">Read more <i class="fa-solid fa-arrow-up-right-from-square"></i></a>`;
        fragment.appendChild(listItem);
        this.foodItems.push(listItem);
      });
      
      if (data.data.foods.length < this.step || data.status === 404) {
        const endOfDataItem = document.createElement('li');
        endOfDataItem.innerHTML = '<div>End of data.</div>';
        fragment.appendChild(endOfDataItem);
      }
    }

    // Clear previous content
    this.container.innerHTML = '';

    // Append the existing items from the array
    this.foodItems.forEach(item => {
      this.container.appendChild(item);
    });

    // Append the new items or error message
    this.container.appendChild(fragment);
  }

  async loadMoreData() {
    this.from += this.step;
    this.to += this.step;
    try {
      const data = await this.fetchData(this.from, this.to, this.searchInput.value);
      this.renderFoodItems(data);
    } catch (error) {
      console.error('Error fetching data:', error);
    }
  }

  clearSearch() {
    // Reset array, from and to values
    this.searchInput.value = '';
    this.searchClear.style.visibility = 'hidden';
    this.from = 0;
    this.to = this.step;
    this.foodItems = [];
    this.fetchData(this.from, this.to)
      .then(data => this.renderFoodItems(data))
      .catch(error => console.error('Error fetching data:', error));
  }

  init() {
    // Event listeners for search input and clear search button
    this.searchInput.addEventListener('input', () => {
      if (this.searchInput.value) {
        this.searchClear.style.visibility = 'visible';
        this.from = 0;
        this.to = this.step;
        this.foodItems = [];
      } else {
        this.searchClear.style.visibility = 'hidden';
      }
      this.fetchData(this.from, this.to, this.searchInput.value)
        .then(data => this.renderFoodItems(data))
        .catch(error => console.error('Error fetching data:', error));
    });

    this.searchClear.addEventListener('click', this.clearSearch);

    // Initial load
    this.fetchData(this.from, this.to)
      .then(data => this.renderFoodItems(data))
      .catch(error => console.error('Error fetching data:', error));

    // Event listener for loading more data
    document.getElementById('load_more').addEventListener('click', this.loadMoreData);

    // Event listener for debounced search
    this.searchInput.addEventListener('keydown', debounce((event) => {
      if (event.key === 'Enter') {
        this.loadMoreData();
      }
    }, 300));
  }
}

// Create FoodList instance
const foodList = new FoodList('food_list_container');
