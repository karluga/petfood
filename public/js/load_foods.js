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
    this.loadMoreData = this.loadMoreData.bind(this);
    this.clearSearch = this.clearSearch.bind(this);
    this.foodItems = [];
    this.init();
  }

  async fetchData(from, to, searchQuery = '', safeOnly = false) {
    try {
      let queryString = `from=${from}&to=${to}&q=${searchQuery}`;
      if (safeOnly) {
        queryString += '&safe_only';
      }
      const response = await fetch(`/api/food_safety/${this.lastSegment}?${queryString}`);
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
    } else {
      this.foodItems = []; // Empty previous results when rendering new ones
      data.data.foods.forEach(food => {
        const listItem = document.createElement('li');
        let htmlString = `<div>${food.food}</div>
                          <div class="item" style="background: ${food.hex_color}">
                            <span class="mr-3">${food.safety_label}</span>`;
        if (food.filename) {
          htmlString += `<img src="${food.filename}" height="40" alt="Icon">`;
        }
        htmlString += `</div><a href="#">Read more <i class="fa-solid fa-arrow-up-right-from-square"></i></a>`;
        listItem.innerHTML = htmlString;
        fragment.appendChild(listItem);
        this.foodItems.push(listItem);
      });
      
      if (data.data.foods.length < this.step || data.status === 404) {
        const endOfDataItem = document.createElement('li');
        endOfDataItem.innerHTML = '<div>End of data.</div>';
        fragment.appendChild(endOfDataItem);
        document.getElementById('load_more').style.display = 'none';
      }
    }

    this.container.innerHTML = '';
    this.foodItems.forEach(item => {
      this.container.appendChild(item);
    });
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
    this.searchInput.addEventListener('input', () => {
      if (this.searchInput.value) {
        this.searchClear.style.visibility = 'visible';
        this.from = 0;
        this.to = this.step;
        this.foodItems = [];
        clearTimeout(debounceTimer);
        debounceTimer = setTimeout(() => {
          this.fetchData(this.from, this.to, this.searchInput.value)
            .then(data => this.renderFoodItems(data))
            .catch(error => console.error('Error fetching data:', error));
        }, 1000); // Wait one second after last keystroke
      } else {
        this.searchClear.style.visibility = 'hidden';
        clearTimeout(debounceTimer);
      }
    });

    this.searchClear.addEventListener('click', this.clearSearch);

    this.fetchData(this.from, this.to)
      .then(data => this.renderFoodItems(data))
      .catch(error => console.error('Error fetching data:', error));

    document.getElementById('load_more').addEventListener('click', this.loadMoreData);

    this.searchInput.addEventListener('keydown', debounce((event) => {
      if (event.key === 'Enter') {
        this.loadMoreData();
      }
    }, 300));

    const safeToFeedCheckbox = document.getElementById('safe_to_feed');
    safeToFeedCheckbox.addEventListener('change', () => {
      this.from = 0;
      this.to = this.step;
      if (!safeToFeedCheckbox.disabled && !this.searchInput.disabled) {
        this.foodItems = []; // Empty previous results when changing checkbox state
        this.fetchData(this.from, this.to, this.searchInput.value, safeToFeedCheckbox.checked)
          .then(data => this.renderFoodItems(data))
          .catch(error => console.error('Error fetching data:', error));
      }
    });

    // Disable checkbox when timeout is active
    setInterval(() => {
      if (rateLimiter.timeout) {
        safeToFeedCheckbox.disabled = true;
      } else {
        safeToFeedCheckbox.disabled = false;
      }
    }, 1000);
  }
}

const foodList = new FoodList('food_list_container');
