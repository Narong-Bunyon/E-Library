@extends('layouts.marketing')

@section('title', 'About Us - E-Library')

@section('content')
<div style="font-family: Arial, sans-serif; line-height: 1.6; color: #333; background: #f8f9fa;">
<!-- Hero Section -->
<section style="background: linear-gradient(135deg, #dbeafe 0%, #bfdbfe 50%, #93c5fd 100%); padding: 120px 0; color: #1e40af; position: relative; overflow: hidden;">
    <div style="max-width: 1200px; margin: 0 auto; padding: 0 20px; text-align: center;">
        <h1 style="font-size: 3.5rem; font-weight: bold; margin-bottom: 20px; color: #1e40af;">About E-Library</h1>
        <p style="font-size: 1.8rem; margin-bottom: 30px; color: #3b82f6;">Your Digital Gateway to Knowledge</p>
        <div style="max-width: 800px; margin: 0 auto;">
            <p style="font-size: 1.2rem; margin-bottom: 40px; color: #64748b; line-height: 1.8;">Making education accessible to everyone through innovative digital solutions and comprehensive learning resources that empower learners worldwide.</p>
            <div style="display: flex; gap: 20px; justify-content: center; flex-wrap: wrap;">
                <a href="{{ route('home') }}" style="background: #3b82f6; color: white; padding: 15px 35px; border-radius: 30px; text-decoration: none; font-weight: bold; font-size: 1.1rem; display: inline-flex; align-items: center; gap: 10px; box-shadow: 0 4px 15px rgba(59, 130, 246, 0.3); transition: all 0.3s ease;">
                    <i class="fas fa-book"></i>
                    Browse Books
                </a>
                <a href="{{ route('categories') }}" style="background: transparent; color: #1e40af; padding: 15px 35px; border-radius: 30px; text-decoration: none; font-weight: bold; font-size: 1.1rem; display: inline-flex; align-items: center; gap: 10px; border: 2px solid #3b82f6; transition: all 0.3s ease;">
                    <i class="fas fa-layer-group"></i>
                    Explore Categories
                </a>
            </div>
        </div>
        <div style="margin-top: 60px; font-size: 10rem; opacity: 0.1; color: #3b82f6;">
            <i class="fas fa-book-open"></i>
        </div>
    </div>
</section>

<!-- Statistics Section -->
<section style="padding: 100px 0; background: white;">
    <div style="max-width: 1200px; margin: 0 auto; padding: 0 20px;">
        <div style="text-align: center; margin-bottom: 60px;">
            <h2 style="font-size: 2.8rem; font-weight: bold; color: #2c3e50; margin-bottom: 20px;">Our Impact in Numbers</h2>
            <p style="font-size: 1.3rem; color: #3b82f6; max-width: 700px; margin: 0 auto;">Join thousands of learners who have already discovered the power of digital reading</p>
        </div>
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 40px;">
            <div style="background: linear-gradient(135deg, #f8f9fa, #ffffff); padding: 40px 30px; border-radius: 20px; text-align: center; box-shadow: 0 10px 30px rgba(0,0,0,0.08); border: 1px solid #e9ecef; transition: all 0.3s ease; position: relative; overflow: hidden;">
                <div style="position: absolute; top: 0; left: 0; right: 0; height: 4px; background: linear-gradient(90deg, #3b82f6, #1d4ed8);"></div>
                <div style="font-size: 3rem; color: #3b82f6; margin-bottom: 20px;">
                    <i class="fas fa-book"></i>
                </div>
                <div style="font-size: 2.5rem; font-weight: 900; color: #2c3e50; margin-bottom: 15px;">{{ App\Models\Book::count() }}+</div>
                <div style="color: #3b82f6; font-weight: 600; font-size: 1.1rem; text-transform: uppercase; letter-spacing: 1px;">Books Available</div>
            </div>
            <div style="background: linear-gradient(135deg, #f8f9fa, #ffffff); padding: 40px 30px; border-radius: 20px; text-align: center; box-shadow: 0 10px 30px rgba(0,0,0,0.08); border: 1px solid #e9ecef; transition: all 0.3s ease; position: relative; overflow: hidden;">
                <div style="position: absolute; top: 0; left: 0; right: 0; height: 4px; background: linear-gradient(90deg, #3b82f6, #1d4ed8);"></div>
                <div style="font-size: 3rem; color: #3b82f6; margin-bottom: 20px;">
                    <i class="fas fa-users"></i>
                </div>
                <div style="font-size: 2.5rem; font-weight: 900; color: #2c3e50; margin-bottom: 15px;">{{ App\Models\User::count() }}+</div>
                <div style="color: #3b82f6; font-weight: 600; font-size: 1.1rem; text-transform: uppercase; letter-spacing: 1px;">Active Users</div>
            </div>
            <div style="background: linear-gradient(135deg, #f8f9fa, #ffffff); padding: 40px 30px; border-radius: 20px; text-align: center; box-shadow: 0 10px 30px rgba(0,0,0,0.08); border: 1px solid #e9ecef; transition: all 0.3s ease; position: relative; overflow: hidden;">
                <div style="position: absolute; top: 0; left: 0; right: 0; height: 4px; background: linear-gradient(90deg, #3b82f6, #1d4ed8);"></div>
                <div style="font-size: 3rem; color: #3b82f6; margin-bottom: 20px;">
                    <i class="fas fa-layer-group"></i>
                </div>
                <div style="font-size: 2.5rem; font-weight: 900; color: #2c3e50; margin-bottom: 15px;">{{ App\Models\Category::count() }}+</div>
                <div style="color: #3b82f6; font-weight: 600; font-size: 1.1rem; text-transform: uppercase; letter-spacing: 1px;">Categories</div>
            </div>
            <div style="background: linear-gradient(135deg, #f8f9fa, #ffffff); padding: 40px 30px; border-radius: 20px; text-align: center; box-shadow: 0 10px 30px rgba(0,0,0,0.08); border: 1px solid #e9ecef; transition: all 0.3s ease; position: relative; overflow: hidden;">
                <div style="position: absolute; top: 0; left: 0; right: 0; height: 4px; background: linear-gradient(90deg, #3b82f6, #1d4ed8);"></div>
                <div style="font-size: 3rem; color: #3b82f6; margin-bottom: 20px;">
                    <i class="fas fa-download"></i>
                </div>
                <div style="font-size: 2.5rem; font-weight: 900; color: #2c3e50; margin-bottom: 15px;">{{ App\Models\Download::count() }}+</div>
                <div style="color: #3b82f6; font-weight: 600; font-size: 1.1rem; text-transform: uppercase; letter-spacing: 1px;">Downloads</div>
            </div>
        </div>
    </div>
</section>

<!-- Features Section -->
<section style="padding: 100px 0; background: #f9fafb;">
    <div style="max-width: 1200px; margin: 0 auto; padding: 0 20px;">
        <div style="text-align: center; margin-bottom: 60px;">
            <h2 style="font-size: 2.8rem; font-weight: bold; color: #2c3e50; margin-bottom: 20px;">Why Choose E-Library?</h2>
            <p style="font-size: 1.3rem; color: #6c757d; max-width: 700px; margin: 0 auto;">Discover the features that make our digital library the perfect choice for your learning journey</p>
        </div>
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(380px, 1fr)); gap: 40px;">
            <div style="background: white; padding: 40px; border-radius: 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.08); border: 1px solid #e9ecef; transition: all 0.3s ease; position: relative; overflow: hidden;">
                <div style="position: absolute; top: 0; left: 0; right: 0; height: 4px; background: linear-gradient(90deg, #3b82f6, #1d4ed8);"></div>
                <div style="width: 80px; height: 80px; background: linear-gradient(135deg, #3b82f6, #1d4ed8); border-radius: 20px; display: flex; align-items: center; justify-content: center; margin-bottom: 30px; font-size: 2rem; color: white; box-shadow: 0 10px 25px rgba(59, 130, 246, 0.3);">
                    <i class="fas fa-book-open"></i>
                </div>
                <h3 style="font-size: 1.5rem; font-weight: bold; color: #2c3e50; margin-bottom: 20px;">Extensive Collection</h3>
                <p style="color: #6c757d; margin-bottom: 25px; line-height: 1.7; font-size: 1.1rem;">Access thousands of books across various genres and subjects, all available at your fingertips.</p>
                <ul style="list-style: none; padding: 0; margin: 0;">
                    <li style="color: #6c757d; margin-bottom: 15px; display: flex; align-items: center; font-size: 1rem;">
                        <i class="fas fa-check-circle" style="color: #28a745; margin-right: 12px; font-size: 1rem;"></i>
                        Multiple genres and categories
                    </li>
                    <li style="color: #6c757d; margin-bottom: 15px; display: flex; align-items: center; font-size: 1rem;">
                        <i class="fas fa-check-circle" style="color: #28a745; margin-right: 12px; font-size: 1rem;"></i>
                        Regularly updated collection
                    </li>
                    <li style="color: #6c757d; margin-bottom: 15px; display: flex; align-items: center; font-size: 1rem;">
                        <i class="fas fa-check-circle" style="color: #28a745; margin-right: 12px; font-size: 1rem;"></i>
                        Premium and free content
                    </li>
                </ul>
            </div>
            <div style="background: white; padding: 40px; border-radius: 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.08); border: 1px solid #e9ecef; transition: all 0.3s ease; position: relative; overflow: hidden;">
                <div style="position: absolute; top: 0; left: 0; right: 0; height: 4px; background: linear-gradient(90deg, #3b82f6, #1d4ed8);"></div>
                <div style="width: 80px; height: 80px; background: linear-gradient(135deg, #3b82f6, #1d4ed8); border-radius: 20px; display: flex; align-items: center; justify-content: center; margin-bottom: 30px; font-size: 2rem; color: white; box-shadow: 0 10px 25px rgba(59, 130, 246, 0.3);">
                    <i class="fas fa-mobile-alt"></i>
                </div>
                <h3 style="font-size: 1.5rem; font-weight: bold; color: #2c3e50; margin-bottom: 20px;">Mobile Friendly</h3>
                <p style="color: #6c757d; margin-bottom: 25px; line-height: 1.7; font-size: 1.1rem;">Enjoy your reading experience on any device, anywhere, anytime with our responsive design.</p>
                <ul style="list-style: none; padding: 0; margin: 0;">
                    <li style="color: #6c757d; margin-bottom: 15px; display: flex; align-items: center; font-size: 1rem;">
                        <i class="fas fa-check-circle" style="color: #28a745; margin-right: 12px; font-size: 1rem;"></i>
                        Responsive design
                    </li>
                    <li style="color: #6c757d; margin-bottom: 15px; display: flex; align-items: center; font-size: 1rem;">
                        <i class="fas fa-check-circle" style="color: #28a745; margin-right: 12px; font-size: 1rem;"></i>
                        Mobile app available
                    </li>
                    <li style="color: #6c757d; margin-bottom: 15px; display: flex; align-items: center; font-size: 1rem;">
                        <i class="fas fa-check-circle" style="color: #28a745; margin-right: 12px; font-size: 1rem;"></i>
                        Offline reading support
                    </li>
                </ul>
            </div>
            <div style="background: white; padding: 40px; border-radius: 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.08); border: 1px solid #e9ecef; transition: all 0.3s ease; position: relative; overflow: hidden;">
                <div style="position: absolute; top: 0; left: 0; right: 0; height: 4px; background: linear-gradient(90deg, #3b82f6, #1d4ed8);"></div>
                <div style="width: 80px; height: 80px; background: linear-gradient(135deg, #3b82f6, #1d4ed8); border-radius: 20px; display: flex; align-items: center; justify-content: center; margin-bottom: 30px; font-size: 2rem; color: white; box-shadow: 0 10px 25px rgba(59, 130, 246, 0.3);">
                    <i class="fas fa-search"></i>
                </div>
                <h3 style="font-size: 1.5rem; font-weight: bold; color: #2c3e50; margin-bottom: 20px;">Smart Search</h3>
                <p style="color: #6c757d; margin-bottom: 25px; line-height: 1.7; font-size: 1.1rem;">Find exactly what you're looking for with our advanced search and filtering capabilities.</p>
                <ul style="list-style: none; padding: 0; margin: 0;">
                    <li style="color: #6c757d; margin-bottom: 15px; display: flex; align-items: center; font-size: 1rem;">
                        <i class="fas fa-check-circle" style="color: #28a745; margin-right: 12px; font-size: 1rem;"></i>
                        Advanced search filters
                    </li>
                    <li style="color: #6c757d; margin-bottom: 15px; display: flex; align-items: center; font-size: 1rem;">
                        <i class="fas fa-check-circle" style="color: #28a745; margin-right: 12px; font-size: 1rem;"></i>
                        Category browsing
                    </li>
                    <li style="color: #6c757d; margin-bottom: 15px; display: flex; align-items: center; font-size: 1rem;">
                        <i class="fas fa-check-circle" style="color: #28a745; margin-right: 12px; font-size: 1rem;"></i>
                        Personalized recommendations
                    </li>
                </ul>
            </div>
        </div>
    </div>
</section>

<!-- Mission Section -->
<section style="padding: 100px 0; background: white;">
    <div style="max-width: 1200px; margin: 0 auto; padding: 0 20px;">
        <div style="text-align: center; margin-bottom: 60px;">
            <h2 style="font-size: 2.8rem; font-weight: bold; color: #2c3e50; margin-bottom: 20px;">Our Mission & Vision</h2>
            <p style="font-size: 1.3rem; color: #6c757d; max-width: 700px; margin: 0 auto;">We're committed to making education accessible to everyone through digital innovation</p>
        </div>
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(450px, 1fr)); gap: 40px;">
            <div style="background: linear-gradient(135deg, #f8f9fa, #ffffff); padding: 50px; border-radius: 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.08); border: 1px solid #e9ecef; transition: all 0.3s ease; position: relative; overflow: hidden;">
                <div style="position: absolute; top: 0; left: 0; right: 0; height: 4px; background: linear-gradient(90deg, #3b82f6, #1d4ed8);"></div>
                <div style="width: 80px; height: 80px; background: linear-gradient(135deg, #3b82f6, #1d4ed8); border-radius: 20px; display: flex; align-items: center; justify-content: center; margin-bottom: 30px; font-size: 2rem; color: white; box-shadow: 0 10px 25px rgba(59, 130, 246, 0.3);">
                    <i class="fas fa-bullseye"></i>
                </div>
                <h3 style="font-size: 1.8rem; font-weight: bold; color: #2c3e50; margin-bottom: 20px;">Our Mission</h3>
                <p style="color: #6c757d; line-height: 1.8; font-size: 1.1rem;">To democratize education by providing free and affordable access to quality digital learning resources for everyone, regardless of their background or location.</p>
            </div>
            <div style="background: linear-gradient(135deg, #f8f9fa, #ffffff); padding: 50px; border-radius: 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.08); border: 1px solid #e9ecef; transition: all 0.3s ease; position: relative; overflow: hidden;">
                <div style="position: absolute; top: 0; left: 0; right: 0; height: 4px; background: linear-gradient(90deg, #3b82f6, #1d4ed8);"></div>
                <div style="width: 80px; height: 80px; background: linear-gradient(135deg, #3b82f6, #1d4ed8); border-radius: 20px; display: flex; align-items: center; justify-content: center; margin-bottom: 30px; font-size: 2rem; color: white; box-shadow: 0 10px 25px rgba(59, 130, 246, 0.3);">
                    <i class="fas fa-eye"></i>
                </div>
                <h3 style="font-size: 1.8rem; font-weight: bold; color: #2c3e50; margin-bottom: 20px;">Our Vision</h3>
                <p style="color: #6c757d; line-height: 1.8; font-size: 1.1rem;">To become the world's most inclusive digital library platform, empowering millions of learners to achieve their educational goals.</p>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section style="padding: 120px 0; background: linear-gradient(135deg, #3b82f6, #1d4ed8); color: white; text-align: center; position: relative;">
    <div style="max-width: 900px; margin: 0 auto; padding: 0 20px; position: relative; z-index: 2;">
        <h2 style="font-size: 3rem; font-weight: bold; margin-bottom: 25px; text-shadow: 0 2px 4px rgba(0,0,0,0.3);">Start Your Learning Journey Today</h2>
        <p style="font-size: 1.4rem; margin-bottom: 40px; opacity: 0.95; line-height: 1.6;">Join thousands of learners who have already discovered the joy of digital reading and transform your learning experience.</p>
        <div style="display: flex; gap: 20px; justify-content: center; flex-wrap: wrap;">
            <a href="{{ route('register') }}" style="background: white; color: #28a745; padding: 18px 40px; border-radius: 30px; text-decoration: none; font-weight: bold; font-size: 1.2rem; display: inline-flex; align-items: center; gap: 12px; box-shadow: 0 8px 25px rgba(0,0,0,0.2); transition: all 0.3s ease;">
                <i class="fas fa-user-plus"></i>
                Get Started Free
            </a>
            <a href="{{ route('home') }}" style="background: rgba(255,255,255,0.2); color: white; padding: 18px 40px; border-radius: 30px; text-decoration: none; font-weight: bold; font-size: 1.2rem; display: inline-flex; align-items: center; gap: 12px; border: 2px solid rgba(255,255,255,0.3); backdrop-filter: blur(10px); transition: all 0.3s ease;">
                <i class="fas fa-book"></i>
                Browse Books
            </a>
        </div>
    </div>
</section>

</div>
@endsection
