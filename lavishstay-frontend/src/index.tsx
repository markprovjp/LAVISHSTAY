import React from "react";
import ReactDOM from "react-dom/client";
import { HelmetProvider } from "react-helmet-async";
import { Provider } from "react-redux";
import store from "./store";
import "./index.css";
import App from "./App";
import "./utils/performanceOptimization";

// Khởi tạo Mirage server trong development mode
// Tạm thời disable để test payment với backend thật
// if (process.env.NODE_ENV === 'development') {
//   import('./mirage/server').then(({ makeServer }) => {
//     makeServer();
//   });
// }

// Disable StrictMode in development to avoid findDOMNode warnings
const AppWrapper = process.env.NODE_ENV === 'development' ?
  ({ children }: { children: React.ReactNode }) => <>{children}</> :
  React.StrictMode;

const root = ReactDOM.createRoot(document.getElementById("root")!);
root.render(
  <AppWrapper>
    <Provider store={store}>
      <HelmetProvider>
        <App />
      </HelmetProvider>
    </Provider>
  </AppWrapper>
);
