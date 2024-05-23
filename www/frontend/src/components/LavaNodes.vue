<template>
  <div class="header">
    <a href="/" class="logo">nodesUP</a>
    <a href="/lava" class="logo">{{ this.online }} / {{ this.all }}</a>
    <div class="header-right">
      <a href="/">Servers</a>
      <a href="/archive-node">Archive Nodes</a>
      <a href="/nibiru">Nibiru</a>
      <a class="active" href="/lava">Lava</a>
      <a href="/exorde">Exorde</a>
    </div>
  </div>

  <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
      <table>
        <thead>
        <tr>
          <td>Node Name</td>
          <td>Location</td>
          <td>Synchronization</td>
          <td>Last Block</td>
        </tr>
        </thead>
        <tbody>
        <tr v-for="node in nodes" v-bind:key="node.nodeName">
          <td>{{ node.nodeName }}</td>
          <td>{{ node.location }}</td>
          <td>{{ node.isSync }}</td>
          <td>{{ node.lastBlock }}</td>
        </tr>
        </tbody>
      </table>
    </div>

</template>

<script>
import axios from "axios";

export default {
  name: "lava-nodes",
  data() {
    return {
      nodes: [],
      online: 0,
      all: 0,
    };
  },
  mounted() {
    this.lava();
  },
  methods: {
    lava() {
      axios.get('/api/v1/lava', {
      }).then(response => {
        console.log(response);
        this.nodes = response.data.result;
        this.online = response.data.online;
        this.all = response.data.all;
      }).catch(error => {
        console.log(error);
      });
    },
  },
  computed: {

  },
}
</script>

<style scoped lang="scss">
/* Style the header with a grey background and some padding */
.header {
  overflow: hidden;
  background-color: #f1f1f1;
  padding: 20px 10px;
}

/* Style the header links */
.header a {
  float: left;
  color: black;
  text-align: center;
  padding: 12px;
  text-decoration: none;
  font-size: 18px;
  line-height: 25px;
  border-radius: 4px;
}

/* Style the logo link (notice that we set the same value of line-height and font-size to prevent the header to increase when the font gets bigger */
.header a.logo {
  font-size: 25px;
  font-weight: bold;
}

/* Change the background color on mouse-over */
.header a:hover {
  background-color: #ddd;
  color: black;
}

/* Style the active/current link*/
.header a.active {
  background-color: rgb(84, 88, 93);
  color: white;
}

/* Float the link section to the right */
.header-right {
  float: right;
}

/* Add media queries for responsiveness - when the screen is 500px wide or less, stack the links on top of each other */
@media screen and (max-width: 500px) {
  .header a {
    float: none;
    display: block;
    text-align: left;
  }
  .header-right {
    float: none;
  }
}

table {
  border-collapse: collapse;
}
table td {
  padding: 15px;
}
table thead td {
  background-color: #54585d;
  color: #ffffff;
  font-weight: bold;
  font-size: 13px;
  border: 1px solid #54585d;
}
table tbody td {
  color: #636363;
  border: 1px solid #dddfe1;
}
table tbody tr {
  background-color: #f9fafb;
  border: 1px solid #dddfe1;
}
table tbody tr:nth-child(odd) {
  background-color: #ffffff;
}
</style>