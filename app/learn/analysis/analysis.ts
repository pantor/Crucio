class AnalysisController {
  readonly API: APIService;
  readonly Collection: CollectionService;
  user: Crucio.User;
  examId: number;
  workedCollection: Crucio.CollectionListItem[];
  count: Crucio.AnalyseCount;
  exam: Crucio.Exam;

  constructor(Page: PageService, Auth: AuthService, API: APIService, Collection: CollectionService) {
    this.API = API;
    this.Collection = Collection;

    Page.setTitleAndNav('Analyse | Crucio', 'Learn');

    this.user = Auth.getUser();

    this.workedCollection = this.Collection.getWorked();
    this.count = this.Collection.analyseCount();

    // Post results, but not which are already saved or are free questions
    for (const question of this.workedCollection) {
      if (!question.mark_answer && question.type > 1) {
        let correct = (question.correct_answer === question.given_result) ? 1 : 0;
        if (question.correct_answer === 0) {
          correct = -1;
        }

        const data = {
          correct,
          given_result: question.given_result,
          question_id: question.question_id,
          user_id: this.user.user_id,
        };
        this.API.post('results', data);
      }
    }

    if (this.Collection.get().type == 'exam') {
      this.API.get(`exams/${Collection.get().exam_id}`).then(result => {
        this.exam = result.data.exam;
      });
    }
  }

  showCorrectAnswerClicked(index: number): void {
    this.workedCollection[index].mark_answer = 1;
    const questionId = this.workedCollection[index].question_id;
    this.Collection.saveMarkAnswer(this.Collection.getIndexOfQuestion(questionId));
  }
}

angular.module('crucioApp').component('analysiscomponent', {
  templateUrl: 'app/learn/analysis/analysis.html',
  controller: AnalysisController,
});
