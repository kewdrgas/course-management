import { Card } from 'antd';
import { Link } from 'react-router-dom';

const Dashboard = () => {
  return (
    <div className="grid gap-4 grid-cols-1 sm:grid-cols-2">
      <Card title="Courses">
        <Link to="/courses">View All Courses</Link>
      </Card>
      <Card title="Create New Course">
        <Link to="/courses/new">Add New Course</Link>
      </Card>
    </div>
  );
};

export default Dashboard;
