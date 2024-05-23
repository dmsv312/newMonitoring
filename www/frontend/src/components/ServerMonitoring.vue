<template>
  <div class="header">
    <a href="/" class="logo">nodesUP</a>
    <div class="header-right">
      <a class="active" href="/">Servers</a>
      <a href="/archive-node">Archive Nodes</a>
      <a href="/nibiru">Nibiru</a>
      <a href="/lava">Lava</a>
      <a href="/exorde">Exorde</a>
    </div>
  </div>

  <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
      <table>
        <thead>
        <tr>
          <td>Node Name</td>
          <td>Space Free (Gb)</td>
          <td>Space Total (Gb)</td>
          <td>Space Usage (%)</td>
          <td>Ram Free (Gb)</td>
          <td>Ram Total (Gb)</td>
          <td>Ram Usage (%)</td>
          <td>CPU Usage (%)</td>
        </tr>
        </thead>
        <tbody>
        <tr v-for="server in servers" v-bind:key="server.name">
          <td>{{ server.name }}</td>
          <td>{{ this.getSpace(server.space_free) }}</td>
          <td>{{ this.getSpace(server.space_total) }}</td>
          <td>{{ this.getUsage(server.space_free, server.space_total) }}</td>
          <td>{{ this.getRam(server.ram_free) }}</td>
          <td>{{ this.getRam(server.ram_total) }}</td>
          <td>{{ this.getUsage(server.ram_free, server.ram_total) }}</td>
          <td>{{ this.getCpu(server.cpu_usage) }}</td>
        </tr>
<!--        <tr v-for="response in responses" class="bg-white border-b dark:bg-gray-900 dark:border-gray-700">-->
<!--          <td class="px-6 py-4">-->
<!--            {{ response.nodeData.nodeName }}-->
<!--          </td>-->
<!--          <td class="px-6 py-4">-->
<!--            {{ response.nodeData.serverName }}-->
<!--          </td>-->
<!--          <td class="px-6 py-4">-->
<!--            {{ response.response.result.sync_info.latest_block_height }}-->
<!--          </td>-->
<!--          <td class="px-6 py-4">-->
<!--            {{ !response.response.result.sync_info.catching_up }}-->
<!--          </td>-->
<!--        </tr>-->
        </tbody>
      </table>
    </div>

</template>

<script>
import axios from "axios";

export default {
  name: "server-monitoring",
  data() {
    return {
      servers: [],
    };
  },
  mounted() {
    this.login();
  },
  methods: {
    login() {
      axios.get('/api/v1/servers', {
      }).then(response => {
        console.log(response);
        this.servers = response.data.servers;
      }).catch(error => {
        console.log(error);
      });
      axios.get('/api/v1/node', {
      }).then(response => {
        console.log(response);
      }).catch(error => {
        console.log(error);
      });
    },
    getSpace(free) {
      return Math.trunc(free / 1024 / 1024);
    },
    getRam(free) {
      return (free / 1024 / 1024).toFixed(2);
    },
    getCpu(free) {
      let num = Number(free);
      return Math.trunc(num);
    },
    getUsage(free, total) {
      return Math.trunc((total - free) / total * 100);
    }
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