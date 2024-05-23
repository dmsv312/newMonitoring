import { createRouter, createWebHistory } from 'vue-router'
import ServerMonitoring from "@/components/ServerMonitoring.vue";
import NibiruNodes from "@/components/NibiruNodes.vue";
import LavaNodes from "@/components/LavaNodes.vue";
import ArchiveNodes from "@/components/ArchiveNodes.vue";
import ExordeNodes from "@/components/ExordeNodes.vue";
import TaikoNodes from "@/components/TaikoNodes.vue";

const routes = [
    {
        path: '/',
        name: 'home',
        component: ServerMonitoring
    },
    {
        path: '/nibiru',
        name: 'nibiru',
        component: NibiruNodes,
    },
    {
        path: '/lava',
        name: 'lava',
        component: LavaNodes,
    },
    {
        path: '/archive-node',
        name: 'archive-node',
        component: ArchiveNodes,
    },
    {
        path: '/exorde',
        name: 'exorde',
        component: ExordeNodes,
    },
    {
        path: '/taiko',
        name: 'taiko',
        component: TaikoNodes,
    },
]
const router = createRouter({
    history: createWebHistory(process.env.BASE_URL),
    routes
})
export default router