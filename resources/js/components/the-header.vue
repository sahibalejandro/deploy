<template>
    <nav class="navbar navbar-expand-sm navbar-dark bg-dark">
        <div class="container">
            <router-link class="navbar-brand" to="/">Deployer</router-link>
            <!-- General Menu -->
            <ul class="navbar-nav mr-auto">
               <li class="navbar-item">
                    <router-link class="nav-link" to="/">Dashboard</router-link>
               </li>
            </ul>

            <!-- User Menu -->
            <ul class="navbar-nav">
                <li class="navbar-item">
                    <a href="#" class="nav-link">
                        Sahib
                        <span class="badge badge-pill badge-secondary">
                            &#183;&#183;&#183;
                        </span>
                    </a>
                </li>
            </ul>
        </div>
    </nav>
</template>
