import { Route } from "@/types/enums";
import Home from "@/views/HostingerTools.vue";
import { translate } from "@/utils/helpers";
export default [
  {
    path: "/",
    name: Route.Base.HOSTINGER_TOOLS,
    meta: {
      title: translate("routes_tools"),
      headerButton:{
        text: translate('hostinger_tools_open_guide'),
        href: 'https://www.hostinger.com/tutorials/how-to-use-hostinger-tools-plugin'
      }
    },
    component: Home,
  },
];
