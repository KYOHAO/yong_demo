<script setup>
</script>

<template>
  <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm mb-0">
    <div class="container">
      <router-link class="navbar-brand fw-bold text-primary-gradient" to="/">資產管理系統</router-link>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ms-auto">
          <li class="nav-item">
            <router-link class="nav-link" to="/" active-class="active fw-bold text-primary">管理後台</router-link>
          </li>
          <li class="nav-item">
            <router-link class="nav-link" to="/view" active-class="active fw-bold text-primary">公開列表</router-link>
          </li>
        </ul>
      </div>
    </div>
  </nav>

  <router-view></router-view>
</template>

<style scoped>
.text-primary-gradient {
  background: linear-gradient(to right, var(--primary-color), var(--primary-light));
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
}
</style>
