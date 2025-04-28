document.getElementById('post-form').addEventListener('submit', function(event) {
    event.preventDefault();
  
    // Lấy giá trị từ form
    const title = document.getElementById('post-title').value;
    const content = document.getElementById('post-content').value;
    const image = document.getElementById('post-image').files[0];
  
    // Tạo một bài đăng mới
    const postItem = document.createElement('div');
    postItem.classList.add('post-item');
  
    // Thêm hình ảnh nếu có
    if (image) {
      const reader = new FileReader();
      reader.onload = function(e) {
        const img = document.createElement('img');
        img.src = e.target.result;
        img.alt = 'Post Image';
        img.classList.add('post-image');
        postItem.appendChild(img);
      }
      reader.readAsDataURL(image);
    }
  
    // Thêm nội dung bài đăng
    const postContent = document.createElement('div');
    postContent.classList.add('post-content');
    postContent.innerHTML = `
      <h3 class="post-title">${title}</h3>
      <p class="post-text">${content}</p>
    `;
    postItem.appendChild(postContent);
  
    // Thêm bài đăng vào đầu danh sách
    const postList = document.querySelector('.post-list');
    postList.insertBefore(postItem, postList.firstChild);
  })