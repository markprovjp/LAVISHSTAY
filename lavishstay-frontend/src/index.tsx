import React from "react";
import ReactDOM from "react-dom/client";
import { HelmetProvider } from "react-helmet-async";
import { Provider } from "react-redux";
import store from "./store";
import "./index.css";
import App from "./App";

// Khởi tạo Mirage server trong development mode
if (process.env.NODE_ENV === 'development') {
  import('./mirage/server').then(({ makeServer }) => {
    makeServer();
  });
}

const root = ReactDOM.createRoot(document.getElementById("root")!);
root.render(
  <React.StrictMode>
    <Provider store={store}>
      <HelmetProvider>
        <App />
      </HelmetProvider>
    </Provider>
  </React.StrictMode>
);
