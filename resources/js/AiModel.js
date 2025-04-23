import { GoogleGenerativeAI, SchemaType } from '@google/generative-ai';
import {uris} from "./routes.js";
const genAI = new GoogleGenerativeAI(import.meta.env.VITE_GOOGLE_GENAI_API_KEY);

const schema = {
    type: SchemaType.ARRAY,
    items: {
        type: SchemaType.OBJECT,
        properties: {
            id: {
                type: SchemaType.INTEGER,
                nullable: false
            },
            question: {
                type: SchemaType.STRING,
                nullable: false,
            },
            answers: {
                type: SchemaType.ARRAY,
                nullable: false,
                items: {
                    type: SchemaType.STRING,
                },
                minItems: 2,
                maxItems: 4
            },
            correctAnswer: {
                type: SchemaType.STRING,
                nullable: false
            },
            difficulty: {
                type: SchemaType.STRING,
                nullable: false,
                enum: ["Easy", "Medium", "Hard"]
            }
        },
        required: ['question', 'answers', 'correctAnswer', 'id', 'difficulty'],
    },
};

const modelJson = genAI.getGenerativeModel({
    model: 'gemini-2.0-flash',
    generationConfig: {
        responseMimeType: 'application/json',
        responseSchema: schema
    }
});

const redirectModal = genAI.getGenerativeModel({
    model:'gemini-2.0-flash',
})
/**
 * Tạo câu hỏi trắc nghiệm tự động dựa trên thông số đầu vào
 * @param {string} topic - Chủ đề cần tạo câu hỏi
 * @param {quizName} quizName - Tên quiz
 * @param {number} numberOfQuestions - Số lượng câu hỏi
 * @param {number} answersPerQuestion - Số đáp án mỗi câu
 * @param {string} difficultyLevel - Độ khó (Easy/Medium/Hard)
 * @param {function} callback - Hàm callback nhận kết quả
 */
export async function generateQuizQuestions(topic,quizName ,numberOfQuestions, answersPerQuestion, difficultyLevel, callback) {
    try {
        const prompt = `
        Generate ${numberOfQuestions} multiple-choice questions about "${topic}".
        These questions will be used for a quiz named "${quizName}".
        Each question should have ${answersPerQuestion} answer options with only 1 correct answer.
        Difficulty level: ${difficultyLevel}.
        Format in JSON according to the defined schema.
        IMPORTANT:
        - Do not include A, B, C, D prefixes in the answers
        - Each answer must be clearly distinct
        - Questions must be closely related to the topic
        - Make the questions educational and appropriate for quiz assessment
        `;

        const response = await modelJson.generateContent(prompt);
        const result = await JSON.parse(response.response.text());
        callback(result);
    } catch (error) {
        callback(null, error);
    }
}

export const handleRedirect = async (prompt)=>{
    try{
        const res = await redirectModal.generateContent(
            `Generate a redirect URL for the following prompt: ${prompt}.

        Routes available: ${uris}
        if the user says the exact path of the route, you should return the route in the router available.
        if user speak about their language you should return the route in the router available .
        When returning a path, it must contain only the path I provided and must not contain double quotes to wrap the path.
        if the prompt is not related to any route, return "null".
        `

        )
        const redirect = res.response.text()
        console.log(redirect)

            if (!redirect.includes("null")) {
                window.location.href = redirect;
            } else {
                console.error("No redirect URL found in the response.");
                alert("Sorry, I can't help with that.");
            }

    }catch(err){
        console.error("Error generating redirect URL:", err);
    }

}
