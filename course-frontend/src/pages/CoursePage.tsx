import { Routes, Route } from 'react-router-dom';
import CourseList from '../components/CourseList';
import NewCourse from './NewCourse';
import CourseDetailsPage from './CourseDetailsPage';
import EditCoursePage from './EditCoursePage';

const CoursePage = () => {
  return (
    <Routes>
      <Route path="/" element={<CourseList />} />
      <Route path="new" element={<NewCourse />} />
      <Route path=":id" element={<CourseDetailsPage />} />
      <Route path=":id/edit" element={<EditCoursePage />} />
    </Routes>
  );
};

export default CoursePage;
