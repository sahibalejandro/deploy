<template>
    <nav class="navbar navbar-expand-sm navbar-dark bg-dark">
        <div class="container">
            <router-link class="navbar-brand" to="/">Deployer</router-link>
            <!-- General Menu -->
            <ul class="navbar-nav mr-auto">
                <li class="navbar-item">
                    <router-link class="nav-link" to="/">Dashboard</router-link>
                </li>
                <li class="navbar-item">
                    <router-link class="nav-link" to="/databases">Databases</router-link>
                </li>
            </ul>

            <!-- User Menu -->
            <ul class="navbar-nav">
                <li class="navbar-item">
                    <a href="#" class="nav-link dropdown-toggle">
                        Sahib
                    </a>
                </li>
            </ul>
        </div>
    </nav>
</template>
