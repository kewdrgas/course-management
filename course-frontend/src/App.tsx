import React from 'react';
import { Routes, Route } from 'react-router-dom';
import { Layout } from 'antd';
import Dashboard from './pages/Dashboard';
import CoursePage from './pages/CoursePage';

const { Header, Content } = Layout;

function App() {
  return (
    <Layout>
      <Header style={{ color: '#fff', fontSize: '18px' }}>
        📚<a href="/"> Course Management</a>
      </Header>
      <Content style={{ padding: '24px' }}>
        <Routes>
          <Route path="/" element={<Dashboard />} />
          <Route path="/courses/*" element={<CoursePage />} />
        </Routes>
      </Content>
    </Layout>
  );
}

export default App;
