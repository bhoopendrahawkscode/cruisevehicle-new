<template>
    <nav>
      <ul class="pagination">
        <li class="page-item" :class="{ 'disabled': currentPage === 1 }">
          <a class="page-link" @click="changePage(1)" aria-label="Previous">
            <span aria-hidden="true">&laquo;</span>
          </a>
        </li>
  
        <li class="page-item" v-for="page in lastPage" :key="page" :class="{ 'active': currentPage === page }">
          <a class="page-link" @click="changePage(page)">{{ page }}</a>
        </li>
  
        <li class="page-item" :class="{ 'disabled': currentPage === lastPage }">
          <a class="page-link" @click="changePage(lastPage)" aria-label="Next">
            <span aria-hidden="true">&raquo;</span>
          </a>
        </li>
      </ul>
    </nav>
  </template>
  
  <script>
  export default {
    props: {
      currentPage: {
        type: Number,
        required: true,
      },
      lastPage: {
        type: Number,
        required: true,
      },
      changePage: {
        type: Function,
        required: true,
      },
    },
  };
  </script>
  
  <style scoped>
  /* Add your pagination styling here */
  </style>