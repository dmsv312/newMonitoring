<template>
  <el-calendar ref="calendar">
    <template #header="{ date }">
      <el-button-group class="w-100 calendar-controls">
        <el-button type="default" class="prev" size="small" @click="selectDate('prev-month')">Prev</el-button>
        <el-button type="default" class="date" size="small" @click="selectDate('today')">{{ date }}</el-button>
        <el-button type="default" class="next" size="small" @click="selectDate('next-month')">Next</el-button>
      </el-button-group>
    </template>
  </el-calendar>
</template>

<script setup>
import {ref} from "vue";

const calendar = ref()
const selectDate = (val) => {
  calendar.value.selectDate(val)
}
</script>

<script>
export default {
  name: "MyCalendar"
}
</script>

<style lang="scss" scoped>
.calendar-controls {
  display: flex;
  flex-direction: row;

  .prev, .next {
    font-size: 0;
    &:after {
      content: '';
      display: block;
      width: 12px;
      height: 21px;
      background: url("../../assets/images/calendar-arrow.svg") no-repeat center center;
    }
  }
  .prev {
    rotate: 180deg;
  }
  .date {
    flex: 1 1 100%;
    font-family: 'Acrom', sans-serif;
    font-style: normal;
    font-weight: 700;
    font-size: 18px;
    line-height: 22px;
    text-align: center;
    color: #2F2D35;
    text-transform: uppercase;
  }
}
</style>